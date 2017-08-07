<?php
/**
 * Plugin Name: Yoast Mass Meta Editor
 * Version: 1.2.0
 * Plugin URI: https://mateffy.me/mass-meta-editor
 * Description: An easy meta data editor for the Yoast SEO plugin.
 * Author: Lukas von Mateffy
 * Author URI: https://mateffy.me
 * Text Domain: mass-meta-editor
 * License: MIT
 */

if (!defined('WP_YMME')) {
  define('WP_YMME', __FILE__);
}

require_once dirname(WP_YMME).'/updates.php';
require_once dirname(WP_YMME).'/rest/init.php';
require_once dirname(WP_YMME).'/admin/menu.php';
