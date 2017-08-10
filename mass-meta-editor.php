<?php

/**
 * Plugin Name: Mass Meta Editor
 * Version: 2.1.0
 * Plugin URI: https://mateffy.me/mass-meta-editor
 * Description: An easy meta data editor for the data of the Yoast SEO plugin by Yoast BV.
 * Author: Lukas von Mateffy
 * Author URI: https://mateffy.me
 * Text Domain: mass-meta-editor
 * License: GPLv2 or later
 */

define('WP_DEBUG', false);

if (!defined('WP_YMME')) {
  define('WP_YMME', __FILE__);
}

require_once dirname(WP_YMME).'/vendor/plugin-update-checker/plugin-update-checker.php';
$ymme_updates = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/Capevace/mass-meta-editor',
	__FILE__,
	'mass-meta-editor',
  12
);

require_once dirname(WP_YMME).'/rest/init.php';
require_once dirname(WP_YMME).'/admin/menu.php';
