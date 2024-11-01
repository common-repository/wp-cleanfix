<?php

namespace WPCleanFix\Tools;

if (!defined('ABSPATH')) {
  exit();
}

class Database
{
  const COMAPCT_POSTFIX = '_compact';
  const BACKUP_POSTFIX = '_backup';
  const TIME_LIMIT = 300;

  protected $tables = [];

  public function __get($name)
  {
    $method_name = 'get' . ucfirst($name) . 'Attribute';

    if (method_exists($this, $method_name)) {
      return call_user_func([$this, $method_name]);
    }
  }

  protected function getTablesAttribute()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $this->tables = [
      $wpdb->commentmeta => __('Comment Meta', 'wp-cleanfix'),
      $wpdb->options => __('Options', 'wp-cleanfix'),
      $wpdb->postmeta => __('Post Meta', 'wp-cleanfix'),
      $wpdb->usermeta => __('User Meta', 'wp-cleanfix'),
    ];

    return $this->tables;
  }

  /**
   * Return the temporary table name from a WordPress table name. Return FALSE on error,
   *
   * @param $original
   *
   * @return bool|string
   */
  public function createTemporaryTable($original)
  {
    if (empty($original)) {
      return false;
    }

    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $table_name = sprintf('%s%s', $original, self::COMAPCT_POSTFIX);

    // Drop table - if exists of not
    $wpdb->hide_errors();
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange
    $result = $wpdb->query($wpdb->prepare('DROP TABLE %i', $table_name));
    $wpdb->show_errors();

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $result = $wpdb->query(
      // phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
      $wpdb->prepare('CREATE TABLE %i LIKE %i', $table_name, $original)
    );

    if (false === $result) {
      return false;
    }

    return $table_name;
  }

  /**
   * Execute a copy from original table and returns an integer corresponding to the number of rows affected/selected.
   * If there is a MySQL error, the function will return FALSE.
   *
   * @param string $tableName A WordPress database ( $wpdb->options )
   * @param bool   $doBackup  Create a backup
   *
   * @return bool|int
   */
  public function compactTable($tableName, $doBackup)
  {
    if (empty($tableName)) {
      return false;
    }

    global $wpdb;

    try {
      $compact_table_name = $this->createTemporaryTable($tableName);

      if (empty($compact_table_name)) {
        return false;
      }

      $result = false;

      switch ($tableName) {
        case $wpdb->commentmeta:
          // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
          $result = $wpdb->query(
            $wpdb->prepare(
              'INSERT INTO %i (comment_id,meta_key,meta_value) SELECT %i.comment_id, %i.meta_key, %i.meta_value FROM %i',
              $compact_table_name,
              $tableName,
              $tableName,
              $tableName,
              $tableName
            )
          );
          break;

        case $wpdb->options:
          // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
          $result = $wpdb->query(
            $wpdb->prepare(
              'INSERT INTO %i (option_name,option_value,autoload) SELECT %i.option_name, %i.option_value, %i.autoload FROM %i',
              $compact_table_name,
              $tableName,
              $tableName,
              $tableName,
              $tableName
            )
          );
          break;

        case $wpdb->usermeta:
          // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
          $result = $wpdb->query(
            $wpdb->prepare(
              'INSERT INTO %i (user_id,meta_key,meta_value) SELECT %i.user_id, %i.meta_key, %i.meta_value FROM %i',
              $compact_table_name,
              $tableName,
              $tableName,
              $tableName,
              $tableName
            )
          );
          break;

        case $wpdb->postmeta:
          // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
          $result = $wpdb->query(
            $wpdb->prepare(
              'INSERT INTO %i (post_id,meta_key,meta_value) SELECT %i.post_id, %i.meta_key, %i.meta_value FROM %i',
              $compact_table_name,
              $tableName,
              $tableName,
              $tableName,
              $tableName
            )
          );
          break;
      }

      if (false === $result) {
        return false;
      }

      // Do a backup copy
      $backupName = sprintf('%s%s', $tableName, self::BACKUP_POSTFIX);

      // Drop table - if exists of not
      $wpdb->hide_errors();
      // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange
      $result = $wpdb->query($wpdb->prepare('DROP TABLE %i', $backupName));
      $wpdb->show_errors();

      if ($doBackup) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $result = $wpdb->query(
          // phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
          $wpdb->prepare('RENAME TABLE %i TO %i', $tableName, $backupName)
        );
      } else {
        $wpdb->hide_errors();
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange
        $result = $wpdb->query($wpdb->prepare('DROP TABLE %i', $tableName));
        $wpdb->show_errors();
      }

      // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
      $result = $wpdb->query(
        $wpdb->prepare('RENAME TABLE %i TO %i', $compact_table_name, $tableName)
      );

      if (false === $result) {
        return false;
      }

      $wpdb->flush();

      return $result;
    } catch (\Exception $e) {
      $this->setMaintenance(false);
      $wpdb->flush();
      return false;
    }
  }

  /**
   * Hardcore maintenance mode
   *
   * @param bool $enable Optional. TRUE to enable a low-level maintenance mode
   */
  public function setMaintenance($enable = true)
  {
    require_once ABSPATH . 'wp-admin/includes/file.php';

    WP_Filesystem();

    global $wp_filesystem;

    // Special .maintenance file
    $file = $wp_filesystem->abspath() . '.maintenance';
    if ($enable) {
      // Create maintenance file to signal that we are upgrading
      $maintenance_string = '<?php $upgrading = ' . time() . '; ?>';
      $wp_filesystem->delete($file);
      $wp_filesystem->put_contents($file, $maintenance_string, FS_CHMOD_FILE);
    } elseif (!$enable && $wp_filesystem->exists($file)) {
      $wp_filesystem->delete($file);
    }
  }

  public function getTableInformation($tableName)
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $info = $wpdb->get_row(
      $wpdb->prepare('SHOW TABLE STATUS LIKE %s', $tableName)
    );

    return $info;
  }
}
