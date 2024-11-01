<?php

namespace WPCleanFix\Modules\Taxonomies;

use WPCleanFix\Modules\Test;

class OrphanPostCategoriesTest extends Test
{
  public function test()
  {
    $issues = $this->getOrphanPostCategories();

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s orphan post category',
                 'You have %s orphan post categories',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [
             'name' => '%s',
           ]
         )
         ->fix( __( 'Fix: click here to safely and permanently delete them.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    $this->deleteOrphanPostCategories();

    return $this;
  }

  public function getName()
  {
    return __( 'Orphan Post Categories', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'Check for unused post categories.', 'wp-cleanfix' );
  }
}
