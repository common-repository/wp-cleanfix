<?php

namespace WPCleanFix\Modules\Options;

use WPCleanFix\Modules\Module;

class OptionsModule extends Module
{
  protected $view = 'module.index';

  protected $tests = [
    'WPCleanFix\Modules\Options\ExpiredSiteTransientTest',
    'WPCleanFix\Modules\Options\ExpiredTransientTest',
  ];

  public function getMetaBoxTitle()
  {
    return __('Options', 'wp-cleanfix');
  }

  /*
    |--------------------------------------------------------------------------
    | Module methods
    |--------------------------------------------------------------------------
    |
    | Here you'll find the module methods used by single test.
    |
    */

  public function getExpiredTransients($prefix = '')
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // get preferences
    $expiry = WPCleanFix()->options->get('Options.expiry_date');

    $timeout = "{$prefix}_transient_timeout_";
    $like = "{$prefix}_transient\_timeout\_%";

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT option_id, option_name, REPLACE(option_name, %s, '') AS transient_name,
        option_value AS expired,
        from_unixtime( option_value ) AS readable
        FROM $wpdb->options
        WHERE option_name LIKE %s
        AND option_value < ( UNIX_TIMESTAMP(NOW()) - %d )
        ORDER BY expired, transient_name",
        $timeout,
        $like,
        $expiry
      )
    );
  }

  public function deleteExpiredTransients($prefix = '')
  {
    global $wpdb;

    $expired = $this->getExpiredTransients($prefix);

    if (WPCleanFix()->options->get('Options.safe_mode')) {
      if ('_site' == $prefix) {
        foreach ($expired as $transient) {
          get_site_transient($transient->transient_name);
        }
      } else {
        foreach ($expired as $transient) {
          get_transient($transient->transient_name);
        }
      }
    } else {
      $option_names = [];
      foreach ($expired as $transient) {
        $option_names[] = $prefix . '_transient_' . $transient->transient_name;
        $option_names[] =
          $prefix . '_transient_timeout_' . $transient->transient_name;
      }

      $options_names = array_map('esc_sql', $option_names);
      $options_names = implode("','", $options_names);

      // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
      return $wpdb->get_results(
        $wpdb->prepare(
          "DELETE FROM $wpdb->options WHERE option_name IN (%s)",
          $options_names
        )
      );
    }

    return false;
  }
}
