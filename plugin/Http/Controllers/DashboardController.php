<?php

namespace WPCleanFix\Http\Controllers;

use WPCleanFix\ActionsAndFiltersJS\ActionsAndFiltersJSProvider;

class DashboardController extends Controller
{
    public function load()
    {
        wp_enqueue_script('common');
        wp_enqueue_script('wp-lists');
        wp_enqueue_script('postbox');

        wp_enqueue_style(
            'wp-cleanfix-dashboard',
            WPCleanFix()->css . '/wp-cleanfix-dashboard.css',
            [],
            WPCleanFix()->Version
        );

        $refs = ActionsAndFiltersJSProvider::enqueueScripts();

        wp_enqueue_script(
            'wp-cleanfix-dashboard',
            WPCleanFix()->js . '/wp-cleanfix-dashboard.js',
            [$refs],
            WPCleanFix()->Version,
            true
        );

        wp_localize_script('wp-cleanfix-dashboard', 'wpCleanFix', [
          'ajaxUrl' => admin_url('admin-ajax.php'),
          'nonce' => wp_create_nonce('wp-cleanfix'),
          'alertMissingUser' => __('You must select a user to fix the issue', 'wp-cleanfix'),
        ]);

        $GLOBALS['WPCleanFixModules']->addMetaBoxes();
    }

    public function index()
    {
        return WPCleanFix()
          ->view('dashboard.index')
          ->with('modules', $GLOBALS['WPCleanFixModules']);
    }
}
