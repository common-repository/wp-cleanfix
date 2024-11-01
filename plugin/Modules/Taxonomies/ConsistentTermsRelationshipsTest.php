<?php

namespace WPCleanFix\Modules\Taxonomies;

use WPCleanFix\Modules\Test;

class ConsistentTermsRelationshipsTest extends Test
{
  public function test()
  {
    $issues = $this->getConsistentTermsRelationships();

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s row broken. This object ID or term taxonomy ID is not valid',
                 'You have %s broken rows. These object ID or terms taxonomy ID are not valid',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [
             'name'     => '%s',
             'taxonomy' => '(%s)',
           ]
         )
         ->fix( __( 'Fix: click here to repair terms relationships.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    $this->deleteConsistentTermsRelationships();

    return $this;
  }

  public function getName()
  {
    return __( 'Consistent Terms/Relationships', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'Check for term_relationships table and for missing taxonomy IDs.', 'wp-cleanfix' );
  }
}
