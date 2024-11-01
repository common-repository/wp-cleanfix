<?php

namespace WPCleanFix\Modules\Posts;

use WPCleanFix\Modules\Test;

class OrphanAttachmentsTest extends Test
{
  public function test()
  {
    // for this method see parent module
    $issues = $this->getPostMetaWithoutPosts();

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s orphan attachment',
                 'You have %s orphan attachments',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [
             'meta_key' => '%s', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
             'number'   => '(%s)'
           ]
         )
         ->fix( __( 'Fix: click here to remove all invalid post parents.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    // for this method see parent module
    $this->deleteAttachmentsWithNullPost();

    return $this;
  }

  public function getName()
  {
    return __( 'Orphan attachments', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'An orphan attachment is a custom post type without a valid parent post ID assigned (it is missing). An attachment usually has a null parent post or a post ID', 'wp-cleanfix' );
  }
}
