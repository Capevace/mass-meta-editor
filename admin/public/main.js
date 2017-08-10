var ymmeHelpers = {
  fetchMetadata: function(callback) {
    jQuery
      .get({
        url: YMME_AJAX_ROOT + '/meta?limit=-1'
      })
      .done(function(result) {
        callback(null, result);
      })
      .error(function(error) {
        callback(error || new Error('Could not fetch metadata.'));
      });
  },
  saveMetadata: function(id, title, description, callback) {
    jQuery
      .post({
        url: YMME_AJAX_ROOT + '/meta',
        data: {
          post_id: id,
          title,
          description
        }
      })
      .done(function(result) {
        callback(null, result);
      })
      .error(function() {
        callback(true);
      });
  }
};

Vue.component('ymme-container', {
  template: `
    <div>
      <h1>Mass Meta Editor</h1>
      <p>
        <a :href="metaDownloadUrl" download="seo-meta.json" class="button button-">Download JSON Data</a>

        <span class="ymme-search">
          Search: <input type="text" placeholder="Search here" v-model="searchQuery" />
          <button class="button" @click="loadMetadata">Refresh</button>
          <!--<a class="button button-secondary" id="ymme-open-settings" href="#ymme-popup">Settings</a>-->
        </span>
      </p>
      <ymme-editor :metadata="filteredMetadata" :placeholders="placeholders" :loading="loading"></ymme-editor>
      <p>By <a href="https://mateffy.me">Lukas von Mateffy</a></p>
    </div>
  `,
  data: function() {
    return {
      metadata: [],
      placeholders: {},
      loading: false,
      searchQuery: ''
    };
  },
  computed: {
    metaDownloadUrl: function() {
      return YMME_AJAX_ROOT + '/meta?limit=-1';
    },
    filteredMetadata: function() {
      var self = this;

      return jQuery.grep(this.metadata, function(element) {
        return element.title.toLowerCase().indexOf(self.searchQuery) !== -1 ||
          element.url.toLowerCase().indexOf(self.searchQuery) !== -1;
      });
    }
  },
  methods: {
    loadMetadata: function() {
      var self = this;
      self.loading = true;

      ymmeHelpers.fetchMetadata(function(err, result) {
        self.loading = false;
        self.metadata = result.metadata;
        self.placeholders = result.placeholders;
      });
    }
  },
  mounted: function() {
    this.loadMetadata();
  }
});

Vue.component('ymme-editor', {
  template: `
    <div class="ymme-editor">
      <div class="ymme-loader" v-if="loading">
        <p>Loading...</p>
      </div>
      <div v-else>
        <table class="ymme-table">
          <thead>
            <tr style="height: 30px;">
              <td>Page Title</td>
              <td>Meta Title</td>
              <td class="bigger">Description</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody>
            <template v-for="(meta, index) in metadata">
              <ymme-meta-table-row :metadata="meta" :placeholders="placeholders" :rowIndex="index"></ymme-meta-table-row>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  `,
  props: ['loading', 'metadata', 'placeholders']
});

Vue.component('ymme-meta-table-row', {
  template: `
    <tr class="meta-object">
      <td>
        <a :href="metadata.url" target="_blank">{{ metadata.title }}</a>
        <a href="#" @click.prevent="openPopup" class="ymme-preview-link">Preview</a>
      </td>
      <td>
        <textarea v-model="titleValue" :placeholder="titlePlaceholder" :class="classForLongTitle"></textarea>
      </td>
      <td>
        <textarea v-model="descriptionValue" :placeholder="placeholders.description" :class="classForLongDescription"></textarea>
      </td>
      <td>
        <button class="button button-ymme" :class="{ 'button-primary': !saveButtonDisabled }" @click="save" :disabled="saveButtonDisabled">Save</button>
      </td>
    </tr>
  `,
  props: ['metadata', 'placeholders', 'rowIndex'],
  computed: {
    titlePlaceholder: function() {
      return (this.placeholders.title || '')
        .replace('%%title%%', this.metadata.title);
    },
    classForLongTitle: function() {
      return this.titleValue.length > 60 ? 'red' : '';
    },
    classForLongDescription: function() {
      return this.descriptionValue.length > 156 ? 'red' : '';
    },
    saveButtonDisabled: function() {
      return this.titleValue === this.metadata.meta.title &&
        this.descriptionValue === this.metadata.meta.description;
    },
    saveButtonClass: function() {
      return '';
    }
  },
  data: function() {
    return {
      titleValue: this.metadata.meta.title,
      descriptionValue: this.metadata.meta.description
    };
  },
  watch: {
    metadata: function() {
      this.titleValue = this.metadata.meta.title;
      this.descriptionValue = this.metadata.meta.description;
    }
  },
  methods: {
    save: function() {
      var self = this;

      ymmeHelpers.saveMetadata(
        this.metadata.post_id,
        this.titleValue,
        this.descriptionValue,
        function(err, result) {
          new Noty({
            text: err
              ? 'An error occurred connecting to the database.'
              : result.msg,
            type: result.error || err ? 'error' : 'success',
            timeout: 4000
          }).show();

          if (!err && !result.error) {
            self.metadata.meta.title = self.titleValue;
            self.metadata.meta.description = self.descriptionValue;
          }
        }
      );
    },
    openPopup: function() {
      var title = this.titleValue || this.titlePlaceholder;
      var description = this.descriptionValue.substring(0, 156) +
        (this.descriptionValue.length > 156 ? '...' : '');

      jQuery.magnificPopup.open({
        items: {
          src: `
            <div class="ymme-popup">
              <span id="ymme-preview-seo-title">${title}</span>
              <span id="ymme-preview-seo-url">${this.metadata.url}</span>
              <span id="ymme-preview-seo-description">${description}</span>
            </div>
          `,
          type: 'inline'
        },
        type: 'inline'
      });
    }
  }
});

var app = new Vue({
  el: '#ymme-app',
  data: {
    message: 'somet'
  }
});
