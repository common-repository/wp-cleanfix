<?php

namespace WPCleanFix\Modules\Database;

use WPCleanFix\Modules\Test;

class DatabaseTablesTest extends Test
{
  protected $tables = [];
  protected $tablesToOptimize = [];
  protected $totalGain = 0;

  public function test()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $issues = [];

    $ignoreINNODB =
      'true' == WPCleanFix()->options->get('Database.ignore_innodb');

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $result = $wpdb->get_results(
      $wpdb->prepare('SHOW TABLE STATUS FROM %i', DB_NAME)
    );

    if (!empty($result)) {
      // Loop into the table list
      foreach ($result as $table) {
        // Exclude innodb by preferences
        if ($ignoreINNODB && 'InnoDB' == $table->Engine) {
          continue;
        }

        // Calculate gain
        $gain = round(floatval($table->Data_free) / 1024, 2);

        // If a gain exist increment the total
        if ($gain > 0) {
          $this->totalGain += $gain;
        }

        // Add this table to complete database table list information
        $this->tables[$table->Name] = [
          'engine' => $table->Engine,
          'gain' => sprintf('%6.2f Kb', $gain),
          'optimize' => $gain > 0,
          'auto_increment' => $table->Auto_increment,
        ];

        // Insert this table and its information in the to optimize list
        if ($gain > 0) {
          $this->tablesToOptimize[$table->Name] = $this->tables[$table->Name];
          $issues[] = (object) [
            'table_name' => $table->Name,
            'gain' => sprintf('%6.2f Kb', $gain),
          ];
        }
      }
    }

    $this->issues($issues)
      ->detailSelect(
        sprintf(
          // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
          _n(
            // phpcs:ignore WordPress.WP.I18n.UnorderedPlaceholdersSingle
            'You have %s table to optimize. This action makes you gain %s',
            // phpcs:ignore WordPress.WP.I18n.UnorderedPlaceholdersPlural
            'You have %s tables to optimize. This action makes you gain %s',
            count($issues),
            'wp-cleanfix'
          ),
          count($issues),
          sprintf('%6.2f Kb', $this->totalGain)
        ),
        [
          'table_name' => '%s',
          'gain' => ' (%s)',
        ]
      )
      ->fix(
        __(
          'Fix: click here in order to optimize your table. This action is safe.',
          'wp-cleanfix'
        )
      );

    return $this;
  }

  public function cleanFix()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Get engine list (no innodb)
    $engine = $this->getEngines();

    $resetAutoIncrement =
      'true' == WPCleanFix()->options->get('Database.reset_auto_increment');
    $ignoreINNODB =
      'true' == WPCleanFix()->options->get('Database.ignore_innodb');

    // Prepare separate list
    $table_to_optimize = [];
    $innodb_tables = [];

    // Loop into the to optimize table list
    foreach ($this->tablesToOptimize as $name => $info) {
      // Separate InnoDB
      if (in_array($info['engine'], $engine)) {
        $table_to_optimize[] = $name;
      } else {
        $innodb_tables[] = $name;
      }
    }

    // MyISAM and other...
    if (!empty($table_to_optimize)) {
      $list_name = join(', ', $table_to_optimize);
      // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
      $result = $wpdb->query($wpdb->prepare('OPTIMIZE TABLE %i', $list_name));
      if (is_wp_error($result)) {
        return $result;
      }
    }

    if ($resetAutoIncrement && !empty($table_to_optimize)) {
      foreach ($table_to_optimize as $table_name) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $result = $wpdb->query(
          // phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
          $wpdb->prepare('ALTER TABLE %i AUTO_INCREMENT = 1', $table_name)
        );

        if (is_wp_error($result)) {
          return $result;
        }
      }
    }

    // InnoDB
    if (!empty($innodb_tables) && !$ignoreINNODB) {
      foreach ($innodb_tables as $innodb_name) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $result = $wpdb->query(
          // phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
          $wpdb->prepare('ALTER TABLE %i ENGINE=InnoDB', $innodb_name)
        );

        if (is_wp_error($result)) {
          return $result;
        }
        if ($resetAutoIncrement) {
          // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
          $result = $wpdb->query(
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
            $wpdb->prepare('ALTER TABLE %i AUTO_INCREMENT = 1', $innodb_name)
          );

          if (is_wp_error($result)) {
            return $result;
          }
        }
      }
    }

    return $this;
  }

  public function tables()
  {
    return $this->tables;
  }

  public function totalGain()
  {
    return $this->totalGain;
  }

  protected function getEngines()
  {
    $engines = ['MyISAM', 'ISAM', 'HEAP', 'MEMORY', 'ARCHIVE'];

    // TODO: Add filter for engines

    return $engines;
  }

  public function getName()
  {
    return __('Database tables', 'wp-cleanfix');
  }

  public function getDescription()
  {
    return __('Here you can see the Database tables status.', 'wp-cleanfix');
  }
}
