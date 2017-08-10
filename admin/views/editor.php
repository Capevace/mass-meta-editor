<script type="text/javascript">
  var YMME_AJAX_ROOT = '<?php echo get_site_url().'/wp-json/ymme/v1'; ?>'
</script>
<script src="<?php echo plugins_url('/admin/public/vue.js', WP_YMME); ?>" charset="utf-8"></script>
<script src="<?php echo plugins_url('/admin/public/noty.js', WP_YMME); ?>" charset="utf-8"></script>
<script src="<?php echo plugins_url('/admin/public/jquery-popup.js', WP_YMME); ?>" charset="utf-8"></script>
<!-- <script src="<?php echo plugins_url('/admin/public/app.js', WP_YMME); ?>" charset="utf-8"></script> -->
<link rel="stylesheet" href="<?php echo plugins_url('/admin/public/style.css', WP_YMME); ?>">
<link rel="stylesheet" href="<?php echo plugins_url('/admin/public/noty.css', WP_YMME); ?>">
<link rel="stylesheet" href="<?php echo plugins_url('/admin/public/jquery-popup.css', WP_YMME); ?>">

<div id="ymme-app" class="wrap">
  <ymme-container></ymme-container>
</div>

<script src="<?php echo plugins_url('/admin/public/main.js', WP_YMME); ?>" charset="utf-8"></script>
