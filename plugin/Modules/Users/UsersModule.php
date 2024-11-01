<?php

namespace WPCleanFix\Modules\Users;

use WPCleanFix\Modules\Module;

class UsersModule extends Module
{
  protected $view = 'module.index';

  protected $tests = ['WPCleanFix\Modules\Users\OrphanUserMetaTest'];

  public function getMetaBoxTitle()
  {
    return __('Users', 'wp-cleanfix');
  }

  /*
  |--------------------------------------------------------------------------
  | Module methods
  |--------------------------------------------------------------------------
  |
  | Here you'll find the module methods used by single test.
  |
  */

  public function getOrphanUserMeta()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results("SELECT DISTINCT( COUNT(*) ) AS number, user_meta.umeta_id, user_meta.meta_key
    FROM $wpdb->usermeta user_meta
    LEFT JOIN $wpdb->users users ON ( users.ID = user_meta.user_id )
    WHERE 1
    AND users.ID IS NULL
    GROUP BY user_meta.meta_key
    ORDER BY user_meta.meta_key");
  }

  public function deleteOrphanUserMeta()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query(
      "DELETE user_meta FROM $wpdb->usermeta user_meta LEFT JOIN $wpdb->users users ON ( users.ID = user_meta.user_id ) WHERE users.ID IS NULL"
    );
  }
}
