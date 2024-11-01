<?php if (!defined('ABSPATH')) {
  exit();
}

WPCleanFix\PureCSSTabs\PureCSSTabsProvider::openTab(
  __('Post Meta', 'wp-cleanfix')
);
?>

  <div class="wp-cleanfix-tools-postmeta-find-replace">
    <form>
      <h3><?php esc_attr_e('Find & Replace', 'wp-cleanfix'); ?></h3>

      <div class="wp-cleanfix-info">
        <?php echo wp_kses_data(
          __(
            'This tool will perform a search for a text string within the <strong>postmeta content</strong> and will replace it with another text. <strong>Be careful because this operation is irreversible.</strong>',
            'wp-cleanfix'
          )
        ); ?>
      </div>

      <p>
        <label for="wp-cleanfix-tools-postmeta-column">
          <?php esc_attr_e('Column', 'wp-cleanfix'); ?>
          <select name="wp-cleanfix-tools-postmeta-column"
                  id="wp-cleanfix-tools-postmeta-column">
            <option value="meta_key">meta_key</option>
            <option value="meta_value">meta_value</option>
          </select>
        </label>
      </p>

      <div class="wp-cleanfix-info">
        <?php echo wp_kses_data(
          __(
            'The fields below are <strong>case sensitive</strong>.',
            'wp-cleanfix'
          )
        ); ?>
      </div>

      <p>
        <label for="wp-cleanfix-tools-postmeta-find">
          <?php esc_attr_e('Find', 'wp-cleanfix'); ?>
          <textarea name="wp-cleanfix-tools-postmeta-find"
                    id="wp-cleanfix-tools-postmeta-find"></textarea>
        </label>
      </p>

      <p>
        <label for="wp-cleanfix-tools-postmeta-replace">
          <?php esc_attr_e('Replace', 'wp-cleanfix'); ?>
          <textarea name="wp-cleanfix-tools-postmeta-replace"
                    id="wp-cleanfix-tools-postmeta-replace"></textarea>
        </label>
      </p>

      <p class="clearfix">
        <button class="button button-secondary wp-clearfix-tools-postmeta-clear-fields alignleft">
          <?php esc_attr_e('Clear Fields', 'wp-cleanfix'); ?>
        </button>
        <button class="button button-primary wp-clearfix-tools-postmeta-find-button alignright">
          <?php esc_attr_e('Find & Replace', 'wp-cleanfix'); ?>
        </button>
      </p>

      <div id="wp-clearfix-tools-postmeta-feedback"></div>

    </form>
  </div>

<?php WPCleanFix\PureCSSTabs\PureCSSTabsProvider::closeTab();
