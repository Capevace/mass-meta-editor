<?php

add_action('admin_menu', 'ymme_admin_menu');

function ymme_admin_menu() {
	add_management_page(
    'Yoast Meta Editor',
    'Yoast Meta Editor',
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
