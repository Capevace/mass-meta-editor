<?php

function ymme_get_meta(WP_REST_Request $request) {
  global $wpdb;

  $limit = 10;
  if (isset($request['limit'])) {
    $limit = intval($request['limit']);
  }

  $query = new WP_Query(array(
    'post_type' => 'page',
    'posts_per_page' => $limit,
    // 'paged' => 6,
  ));

  $sqlpart = "SELECT * FROM $wpdb->postmeta WHERE";
  $post_ids = array();
  $titles = array();

  $index = 0;
  foreach ($query->posts as $post) {
    if ($index === 0) {
      $sqlpart .= ' post_id = %d';
    } else {
      $sqlpart .= ' OR post_id = %d';
    }
    $titles[strval($post->ID)] = array(
      'post_id' => $post->ID,
      'title' => $post->post_title,
      'url' => get_permalink($post)
    );
    array_push($post_ids, $post->ID);

    $index++;
  }

  $sql = call_user_func_array(array($wpdb, 'prepare'), array_merge(array($sqlpart), $post_ids));

  $posts = array();

  foreach ($wpdb->get_results($sql) as $result) {
    $pid = strval($result->post_id);
    $post;
    if (!array_key_exists($pid, $posts)) {
      $t = $titles[$pid];
      $post = array(
        'post_id' => $result->post_id,
        'meta' => array(
          'title' => '',
          'description' => ''
        ),
        'title' => $t['title'],
        'url' => $t['url']
      );
    } else {
      $post = $posts[$pid];
    }

    if ($result->meta_key === '_yoast_wpseo_title') {
      $post['meta']['title'] = $result->meta_value;
    } else if ($result->meta_key === '_yoast_wpseo_metadesc') {
      $post['meta']['description'] = $result->meta_value;
    }

    $posts[$pid] = $post;
  }

  $seo_options = WPSEO_Options::get_option('wpseo_titles');

  return array(
    'data' => array_values($posts),
    'placeholders' => array(
      'title' => $seo_options['title-page'],
      'description' => $seo_options['metadesc-page']
    )
  );
}


function ymme_post_meta(WP_REST_Request $request)
{
  global $wpdb;
  $data = array();

  $post_id = intval($request['post_id']);

  $meta_exists_sql = "SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s";
  $title_exists_query = $wpdb->prepare($meta_exists_sql, $post_id, '_yoast_wpseo_title');
  $desc_exists_query = $wpdb->prepare($meta_exists_sql, $post_id, '_yoast_wpseo_metadesc');
  $error_occured = false;

  if (isset($request['title'])){
    $query = '';

    // if title row already exists
    if (!!$wpdb->get_row($title_exists_query)) {
      $query = $wpdb->prepare(
        "UPDATE $wpdb->postmeta SET meta_value = %s WHERE post_id = %d AND meta_key = %s",
        $request['title'],
        $post_id,
        '_yoast_wpseo_title'
      );
    } else {
      $query = $wpdb->prepare(
        "INSERT INTO $wpdb->postmeta (post_id,meta_key,meta_value) VALUES (%d,%s,%s)",
        $post_id,
        '_yoast_wpseo_title',
        $request['title']
      );
    }

    $wpdb->query($query);

    if ($wpdb->last_error) {
      $error_occured = true;
    }
  }

  if (isset($request['description'])){
    $query = '';

    // if description row already exists
    if (!!$wpdb->get_row($desc_exists_query)) {
      $query = $wpdb->prepare(
        "UPDATE $wpdb->postmeta SET meta_value = %s WHERE post_id = %d AND meta_key = %s",
        $request['description'],
        $post_id,
        '_yoast_wpseo_metadesc'
      );
    } else {
      $query = $wpdb->prepare(
        "INSERT INTO $wpdb->postmeta (post_id,meta_key,meta_value) VALUES (%d,%s,%s)",
        $post_id,
        '_yoast_wpseo_metadesc',
        $request['description']
      );
    }

    $wpdb->query($query);

    if ($wpdb->last_error) {
      $error_occured = true;
    }
  }

  return array(
    'msg' => ($error_occured)
      ? 'An error occurred executing the sql queries.'
      : 'Successfully updated meta records.',
    'error' => $error_occured
  );
}

function ymme_check_updates() {
  global $ymme_updates;
  $ymme_updates->checkForUpdates();

  return array(
    'checked' => true
  );
}
