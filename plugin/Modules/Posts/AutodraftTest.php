<?php

namespace WPCleanFix\Modules\Posts;

use WPCleanFix\Modules\Test;

class AutodraftTest extends Test
{
  public function test()
  {
    $issues = $this->getPostsWithStatus();

    $count = isset( $issues[0]->number ) ? $issues[0]->number : 0;

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s post in autodraft',
                 'You have %s posts in autodraft',
                 $count, 'wp-cleanfix'
             ),
             $count
           ),
           [
             'post_title' => '%s',
             'number'     => '(%s)'
           ]
         )
         ->fix( __( 'Fix: click here to delete the auto drafted posts. This action is safe.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    $this->deletePostsWithStatus();

    return $this;
  }

  public function getName()
  {
    return __( 'Autodraft', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'Post in Auto Draft. WordPress saves an Auto Draft in the database every n seconds. The Auto draft is different from draft, however you can safely remove it to gain more space in the database.', 'wp-cleanfix' );
  }
}
