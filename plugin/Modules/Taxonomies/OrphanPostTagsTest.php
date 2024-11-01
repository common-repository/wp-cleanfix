<?php

namespace WPCleanFix\Modules\Taxonomies;

use WPCleanFix\Modules\Test;

class OrphanPostTagsTest extends Test
{
  public function test()
  {
    $issues = $this->getOrphanPostTags();

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s orphan tag',
                 'You have %s orphan tags',
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
    $this->deleteOrphanPostTags();

    return $this;
  }

  public function getName()
  {
    return __( 'Orphan Post Tags', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'Check for unused post tags.', 'wp-cleanfix' );
  }
}
