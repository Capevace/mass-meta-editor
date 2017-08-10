<?php

add_action('admin_menu', 'ymme_admin_menu');
add_action('admin_enqueue_scripts', 'ymme_admin_dependencies');

function ymme_admin_menu() {
	add_management_page(
    'Mass Meta Editor',
    'Mass Meta Editor',
    'manage_options',
    'ymme/editor.php',
    'ymme_editor_page',
    'dashicons-tickets',
    75
  );
}

function ymme_editor_page() {
  if (is_plugin_active('wordpress-seo/wp-seo.php'))
    include_once dirname(WP_YMME).'/admin/views/editor.php';
  else
    ymme_yoast_missing_notice();
}

function ymme_admin_dependencies($hook) {
  if($hook !== 'tools_page_ymme/editor') {
    return;
  }
}

function ymme_yoast_missing_notice() {
  ?>
    <div class="notice notice-error" style="margin-left: 2px;"> 
      <h3>The Yoast SEO plugin is not installed/active.</h3>
      <p>To use <code>Mass Meta Editor</code>, you need to install the Yoast SEO plugin.</p>
    </div>
  <?php
}