<?php

/**
 * Plugin Name: WP CleanFix
 * Plugin URI: http://undolog.com
 * Description: The all in one tool for check, repair, fix and optimize your WordPress blog
 * Version: 5.7.3
 * Author: Giovambattista Fazioli
 * Author URI: http://undolog.com
 * Text Domain: wp-cleanfix
 * Domain Path: localization
 *
 */

if (!defined('ABSPATH')) {
    exit();
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require_once __DIR__ . '/bootstrap/autoload.php';
