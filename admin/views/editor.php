<script type="text/javascript">
  var YMME_AJAX_ROOT = '<?php echo get_site_url().'/wp-json/ymme/v1'; ?>'
</script>
<script src="<?php echo plugins_url('/admin/public/noty.js', WP_YMME); ?>" charset="utf-8"></script>
<script src="<?php echo plugins_url('/admin/public/jquery-popup.js', WP_YMME); ?>" charset="utf-8"></script>
<script src="<?php echo plugins_url('/admin/public/app.js', WP_YMME); ?>" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo plugins_url('/admin/public/style.css', WP_YMME); ?>">
<link rel="stylesheet" href="<?php echo plugins_url('/admin/public/noty.css', WP_YMME); ?>">
<link rel="stylesheet" href="<?php echo plugins_url('/admin/public/jquery-popup.css', WP_YMME); ?>">

<div class="wrap" id="ymme-editor-wrapper">
  <h1>Yoast Meta Editor</h1>
  <h4 style="margin: 0;">By <a href="https://mateffy.me">Lukas von Mateffy</a></h4>
  <p>
    <a href="<?php echo get_site_url().'/wp-json/ymme/v1'; ?>/meta?limit=-1" download="seo-meta.json" class="button button-">Download JSON Data</a>

    <span class="ymme-search">
      Search: <input type="text" id="ymme-search-field" placeholder="Search here" />
      <button class="button button-secondary" id="ymme-refresh">Refresh</button>
      <!--<a class="button button-secondary" id="ymme-open-settings" href="#ymme-popup">Settings</a>-->
    </span>
  </p>
  <div id="ymme-editor">
    <div id="ymme-loader">
      <p>Loading...</p>
    </div>
    <table id="editor-table" class="ymme-table" style="display: none;">
      <thead>
        <tr>
          <td>
            Page Title
          </td>
          <td>
            Meta Title
          </td>
          <td class="bigger">
            Description
          </td>
          <td>
            Action
          </td>
        </tr>
      </thead>
      <tbody id="editor-table-body">
      </tbody>
    </table>
  </div>
</div>

<div id="ymme-preview-popup" class="ymme-popup mfp-hide">
  <span id="ymme-preview-seo-title">Title</span>
  <span id="ymme-preview-seo-url">https://google.com</span>
  <span id="ymme-preview-seo-description">Description</span>
</div>
