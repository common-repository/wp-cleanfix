<?php

namespace WPCleanFix\Modules\Taxonomies;

use WPCleanFix\Modules\Test;

class OrphanTermsTest extends Test
{
  public function test()
  {
    $issues = $this->getOrphanTerms();

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s orphan generic term',
                 'You have %s orphan generic terms',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [
             'name'     => '%s',
             'taxonomy' => ' (%s)'
           ]
         )
         ->fix( __( 'Fix: click here to delete all orphan terms.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    $this->deleteOrphanTerms();

    return $this;
  }

  public function getName()
  {
    return __( 'Orphan Terms', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'Check for unused generic terms.', 'wp-cleanfix' );
  }
}
