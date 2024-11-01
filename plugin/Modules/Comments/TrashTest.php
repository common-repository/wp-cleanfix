<?php

namespace WPCleanFix\Modules\Comments;

use WPCleanFix\Modules\Test;

class TrashTest extends Test
{
  public function test()
  {
    $issues = $this->getCommentsWithApproved( 'trash' );

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s comment in your trash',
                 'You have %s comments in your trash',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [
             'comment_author'  => '(%s)',
             'comment_content' => '%s'
           ]
         )
         ->fix( __( 'Fix: click here to delete all comments in your trash.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    $this->deleteCommentsWithApproved( 'trash' );

    return $this;
  }

  public function getName()
  {
    return __( 'Trash', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'Comments trash', 'wp-cleanfix' );
  }
}
