<?php

namespace WPCleanFix\Modules\Posts;

use WPCleanFix\Modules\Test;

class TrashTest extends Test
{
  public function test()
  {
    // for this method see parent module
    $issues = $this->getPostsWithStatus( 'trash' );

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s post in trash',
                 'You have %s posts in trash',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [
             'post_title' => '%s',
             'number'     => '(%s)'
           ]
         )
         ->confirm( __( 'Warning! Are you sure to delete your trashed posts permanently?', 'wp-cleanfix' ) )
         ->fix( __( 'Fix: click here to delete your posts in trash.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    // for this method see parent module
    $this->deletePostsWithStatus( 'trash' );

    return $this;
  }

  public function getName()
  {
    return __( 'Trash', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'Post in trash.', 'wp-cleanfix' );

  }
}
