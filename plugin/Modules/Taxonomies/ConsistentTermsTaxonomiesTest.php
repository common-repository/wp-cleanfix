<?php

namespace WPCleanFix\Modules\Taxonomies;

use WPCleanFix\Modules\Test;

class ConsistentTermsTaxonomiesTest extends Test
{
  public function test()
  {
    $issues = $this->getConsistentTermsTaxonomies();

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s orphan taxonomy. This is taxonomy have a not valid term id linked',
                 'You have %s orphan taxonomies. These taxonomies have not a valid term id linked',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [ 'taxonomy' => '%s' ]
         )
         ->fix( __( 'Fix: click here to repair terms and taxonomies.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    $this->deleteConsistentTermsTaxonomies();

    return $this;
  }

  public function getName()
  {
    return __( 'Consistent Terms/Taxonomies', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'These are orphan Taxonomies which are missing a valid linked term.', 'wp-cleanfix' );
  }
}
