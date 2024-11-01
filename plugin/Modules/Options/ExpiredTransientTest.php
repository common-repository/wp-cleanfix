<?php

namespace WPCleanFix\Modules\Options;

use WPCleanFix\Modules\Test;

class ExpiredTransientTest extends Test
{
    public function test()
    {
        $issues = $this->getExpiredTransients();

        $this->issues($issues)
         ->detailSelect(
             sprintf(
                // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
               _n(
                 'You have %s expired transient',
                 'You have %s expired transients',
                 count($issues),
                 'wp-cleanfix'
             ),
               count($issues)
           ),
             [
             'transient_name' => '%s',
             'expired'        => '(%s)'
           ]
         )
         ->fix(__('Fix: click here to delete your expired transients.', 'wp-cleanfix'));

        return $this;
    }

    public function cleanFix()
    {
        $this->deleteExpiredTransients();

        return $this;
    }

    public function getName()
    {
        return __('Expired Transients', 'wp-cleanfix');
    }

    public function getDescription()
    {
        return __('Transients data are temporary values store in the options database table. When a transient is expired you can remove it in safe.', 'wp-cleanfix');
    }
}
