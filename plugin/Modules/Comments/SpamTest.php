<?php

namespace WPCleanFix\Modules\Comments;

use WPCleanFix\Modules\Test;

class SpamTest extends Test
{
  public function test()
  {
    $issues = $this->getCommentsWithApproved( 'spam' );

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s comment marked as spam',
                 'You have %s comments marked as spam',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [
             'comment_author'  => '(%s)',
             'comment_content' => '%s'
           ]
         )
         ->fix( __( 'Fix: click here to delete your SPAM comments.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    $this->deleteCommentsWithApproved( 'spam' );

    return $this;
  }

  public function getName()
  {
    return __( 'Spam', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'These comments are marked as spam.', 'wp-cleanfix' );
  }
}
