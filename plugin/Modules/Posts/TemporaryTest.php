<?php

namespace WPCleanFix\Modules\Posts;

use WPCleanFix\Modules\Test;

class TemporaryTest extends Test
{
  public function test()
  {
    // for this method see parent module
    $issues = $this->getTemporaryPostMeta();

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s temporary post meta',
                 'You have %s temporary posts meta',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [
             'meta_key' => '%s', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
             'number'   => '(%s)'
           ]
         )
         ->fix( __( 'Fix: click here to safely and permanently delete them.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    // for this method see parent module
    $this->deleteTemporaryPostMeta();

    return $this;
  }

  public function getName()
  {
    return __( 'Temporary', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'These records are stored by WordPress as temporary data. If you like you can safely delete them.', 'wp-cleanfix' );

  }
}
