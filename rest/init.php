<?php

require_once dirname(WP_YMME).'/rest/api.php';

add_action('rest_api_init', function () {
  register_rest_route('ymme/v1', '/meta', array(
    'methods' => 'GET',
    'callback' => 'ymme_get_meta',
  ));

  register_rest_route('ymme/v1', '/meta', array(
    'methods' => 'POST',
    'callback' => 'ymme_post_meta',
  ));

  register_rest_route('ymme/v1', '/check-plugin-updates', array(
    'methods' => 'POST',
    'callback' => 'ymme_check_updates',
  ));
});
