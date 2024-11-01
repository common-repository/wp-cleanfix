<?php

namespace WPCleanFix\Modules\Comments;

use WPCleanFix\Modules\Test;

class UnapprovedTest extends Test
{
  public function test()
  {
    $issues = $this->getCommentsWithApproved();

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s comment unapproved',
                 'You have %s comments unapproved',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [
             'comment_author'  => '(%s)',
             'comment_content' => '%s'
           ]
         )
         ->fix( __( 'Fix: click here to delete your unapproved comments.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    $this->deleteCommentsWithApproved( );

    return $this;
  }

  public function getName()
  {
    return __( 'Unapproved', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'Comments unapproved', 'wp-cleanfix' );
  }
}
