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
  include_once dirname(WP_YMME).'/admin/views/editor.php';
}

function ymme_admin_dependencies($hook) {
  if($hook !== 'tools_page_ymme/editor') {
    return;
  }


}
