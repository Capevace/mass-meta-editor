(function() {
  jQuery.fn.extend({
    disable: function(state) {
      return this.each(function() {
        this.disabled = state;
      });
    }
  });
  jQuery(document).ready(ready);

  var $editor, $editorTable, $editorTableBody, $loader;
  var metaData = [];
  var placeholders = {};

  function ready() {
    $editor = jQuery('#ymme-editor');
    $loader = $editor.find('#ymme-loader');
    $editorTable = $editor.find('#editor-table');
    $editorTableBody = $editorTable.find('tbody');

    jQuery('#ymme-search-field').on('input', function (e) {
      var val = e.target.value.toLowerCase();
      var data = jQuery.grep(metaData, function (element) {
        return element.title.toLowerCase().indexOf(val) !== -1
          || element.url.toLowerCase().indexOf(val) !== -1
      });
      renderData(
        data, 
        false,
        placeholders
      );
    });

    jQuery('#ymme-refresh').click(function(event) {
      load();
    });

    function load() {
      renderData([], true, {});
      getMetaElements(null, function(err, result) {
        metaData = result.data;
        placeholders = result.placeholders;

        renderData(metaData, false, placeholders);
      });
    }

    load();
  }

  function renderData(metaElements, loading, placeholders) {
    if (loading) {
      $loader.show();
      $editorTable.hide();
    } else {
      $loader.hide();
      $editorTable.show();

      $editorTableBody.html('');

      for (var i = 0; i < metaElements.length; i++) {
        var meta = metaElements[i];
        $editorTableBody.append(createRow(meta, placeholders));
      }
    }
  }

  function getLoader() {
    return 'Loading Meta Data...';
  }

  function createRow(metaData, placeholders) {
    var row = jQuery('<tr class="meta-object"></tr>').attr(
      'data-id',
      metaData.post_id
    );
    var titleEdited, descEdited = false;
    var saveBtn = jQuery(
      '<button class="button button-ymme">Save</button>'
    ).click(saveMeta.bind(null, metaData.post_id)).disable(true);

    row.append(jQuery('<td></td>').append(
      jQuery('<a></a>')
        .text(metaData.title)
        .attr('href', metaData.url)
        .attr('target', '_blank')
    ));
    row.append(
      jQuery('<td></td>').append(
        jQuery(
          '<textarea></textarea>'
        )
          .on('input', function(e) {
            if (e.target.value.length > 60) {
              jQuery(e.target).addClass('red');
            } else {
              jQuery(e.target).removeClass('red');
            }

            if (e.target.value !== metaData.meta.title) {
              titleEdited = true;
              saveBtn
                .disable(false)
                .addClass('button-primary');
            } else if (!descEdited) {
              titleEdited = false;
              saveBtn
                .disable(true)
                .removeClass('button-primary');
            } else {
              titleEdited = false;
            }

            console.log(descEdited, titleEdited);
          })
          .addClass(metaData.meta.title.length > 60 ? 'red' : '')
          .addClass('title-field')
          .text(metaData.meta.title)
          .attr('placeholder', (placeholders.title || '').replace('%%title%%', metaData.title))
      )
    );
    row.append(
      jQuery('<td></td>').append(
        jQuery('<textarea></textarea>')
          .on('input', function(e) {
            if (e.target.value.length > 156) {
              jQuery(e.target).addClass('red');
            } else {
              jQuery(e.target).removeClass('red');
            }

            if (e.target.value !== metaData.meta.description) {
              descEdited = true;
              saveBtn
                .disable(false)
                .addClass('button-primary');
            } else if (!titleEdited) {
              descEdited = false;
              saveBtn
                .disable(true)
                .removeClass('button-primary');
            } else {
              descEdited = false;
            }
          })
          .addClass(metaData.meta.description.length > 156 ? 'red' : '')
          .addClass('description-field')
          .text(metaData.meta.description)
          .attr('placeholder', placeholders.description)
      )
    );

    row.append(saveBtn);

    return row;
  }

  function getMetaElements(options, callback) {
    jQuery
      .get({
        url: YMME_AJAX_ROOT + '/meta?limit=-1'
      })
      .done(function(result) {
        callback(null, result);
      });
  }

  function saveMeta(id) {
    var row = $editorTableBody.find('.meta-object[data-id="' + id + '"]');
    var titleField = row.find('.title-field');
    var descriptionField = row.find('.description-field');
    var saveBtn = row.find('button');
    var payload = {
      post_id: id,
      title: titleField.val(),
      description: descriptionField.val()
    };

    titleField.disable(true);
    descriptionField.disable(true);
    saveBtn.disable(true);

    jQuery
      .post({
        url: YMME_AJAX_ROOT + '/meta',
        data: payload
      })
      .done(function(result) {
        console.log(result);
        new Noty({
          text: result.msg,
          type: result.error ? 'error' : 'success',
          timeout: 4000
        }).show();
      })
      .always(function() {
        titleField.disable(false);
        descriptionField.disable(false);
        saveBtn.disable(false);
      });
  }

  window.createRow = createRow;
})(jQuery);
