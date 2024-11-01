<?php

namespace WPCleanFix\Modules\Taxonomies;

use WPCleanFix\Modules\Test;

class ConsistentTermsTest extends Test
{
  public function test()
  {
    $issues = $this->getConsistentTerms();

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s orphan term. This term does not exist in the taxonomy',
                 'You have %s orphan terms. This terms do not exist as a taxonomy',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [ 'name' => '%s' ]
         )
         ->fix( __( 'Fix: click here to repair terms', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    $this->deleteConsistentTerms();

    return $this;
  }

  public function getName()
  {
    return __( 'Consistent Terms', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'These are orphan Terms and they don\'t exist in the taxonomy table.', 'wp-cleanfix' );
  }
}
