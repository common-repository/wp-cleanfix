<?php

namespace WPCleanFix\Modules\Options;

use WPCleanFix\Modules\Test;

class ExpiredSiteTransientTest extends Test
{
  public function test()
  {
    $issues = $this->getExpiredTransients( '_site' );

    $this->issues( $issues )
         ->detailSelect(
           sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
             _n( 'You have %s expired site transient',
                 'You have %s expired site transients',
                 count( $issues ), 'wp-cleanfix'
             ),
             count( $issues )
           ),
           [
             'transient_name' => '%s',
             'expired'        => '(%s)'
           ]// phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
         )
         ->fix( __( 'Fix: click here to delete your expired site transients.', 'wp-cleanfix' ) );

    return $this;
  }

  public function cleanFix()
  {
    $this->deleteExpiredTransients( '_site' );

    return $this;
  }

  public function getName()
  {
    return __( 'Expired Site Transients', 'wp-cleanfix' );
  }

  public function getDescription()
  {
    return __( 'Transients data are temporary values stored in the options database tables. When a transient expires you can safely remove it.', 'wp-cleanfix' );
  }
}
