<?php

namespace WPCleanFix\Modules\Users;

use WPCleanFix\Modules\Test;

class OrphanUserMetaTest extends Test
{
  public function test()
  {
    $issues = $this->getOrphanUserMeta();

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s orphan user meta data',
                 'You have %s orphan user meta data',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [
             'meta_key' => '%s', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
             'number'   => ' (%s)'
           ]
         )
         ->fix( __( 'Fix: click here to safely and permanently delete them.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    $this->deleteOrphanUserMeta();

    return $this;
  }

  public function getName()
  {
    return __( 'Orphan user meta', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'User Meta data not correctly assigned to a user. These are extra properties assigned to each user. It might be the case that some records are not assigned to a specific user.', 'wp-cleanfix' );
  }
}
