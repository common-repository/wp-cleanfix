<?php

namespace WPCleanFix\Http\Controllers;

use WPCleanFix\Tools\Database;
use WPCleanFix\Tools\Posts;
use WPCleanFix\Tools\Comments;
use WPCleanFix\Tools\Postmeta;
use WPCleanFix\PureCSSTabs\PureCSSTabsProvider;
use WPCleanFix\PureCSSSwitch\PureCSSSwitchProvider;

class ToolsController extends Controller
{
    public function index()
    {
        // enqueue pure css tabs
        PureCSSTabsProvider::enqueueStyles();

        PureCSSSwitchProvider::enqueueStyles();

        $with = [
          'database' => new Database(),
          'posts' => new Posts(),
          'comments' => new Comments(),
          'postmeta' => new Postmeta(),
        ];

        wp_enqueue_script(
            'wp-cleanfix-tools',
            WPCleanFix()->js . '/wp-cleanfix-tools.js',
            [],
            WPCleanFix()->Version,
            true
        );

        wp_localize_script('wp-cleanfix-tools', 'wpCleanFix', [
          'ajaxUrl' => admin_url('admin-ajax.php'),
          'nonce' => wp_create_nonce('wp-cleanfix'),
        ]);

        return WPCleanFix()
          ->view('tools.index')
          ->with($with)
          ->withAdminStyles('wp-cleanfix-tools');
    }
}
