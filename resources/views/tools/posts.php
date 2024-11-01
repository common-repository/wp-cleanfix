<?php if (!defined('ABSPATH')) {
  exit();
}

WPCleanFix\PureCSSTabs\PureCSSTabsProvider::openTab(__('Posts', 'wp-cleanfix'));
?>

<div class="wp-cleanfix-tools-posts-find-replace">
  <form>
    <h3><?php esc_attr_e('Find & Replace', 'wp-cleanfix'); ?></h3>

    <div class="wp-cleanfix-info">
      <?php echo wp_kses_data(
        __(
          'This tool will perform a search for a text string within the <strong>posts content</strong> and will replace it with another text. <strong>Be careful because this operation is irreversible.</strong>',
          'wp-cleanfix'
        )
      ); ?>
    </div>

    <p>
      <label for="wp-cleanfix-tools-posts-posttypes">
        <?php esc_attr_e('Post Type', 'wp-cleanfix'); ?>
        <select name="wp-cleanfix-tools-posts-posttypes"
                id="wp-cleanfix-tools-posts-posttypes">
          <option value=""><?php esc_attr_e('All'); ?></option>
          <?php foreach ($posts->getPostTypes() as $key => $value): ?>
            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr(
  $value
); ?></option>
          <?php endforeach; ?>
        </select>
      </label>
    </p>

    <p>
      <label for="wp-cleanfix-tools-posts-poststatus">
        <?php esc_attr_e('Post Status', 'wp-cleanfix'); ?>
        <select name="wp-cleanfix-tools-posts-poststatus"
                id="wp-cleanfix-tools-posts-poststatus">
          <option value=""><?php esc_attr_e('All'); ?></option>
          <?php foreach ($posts->getPostStatuses() as $key => $value): ?>
            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr(
  $value
); ?></option>
          <?php endforeach; ?>
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
      <label for="wp-cleanfix-tools-posts-find">
        <?php esc_attr_e('Find', 'wp-cleanfix'); ?>
        <textarea name="wp-cleanfix-tools-posts-find"
                  id="wp-cleanfix-tools-posts-find"></textarea>
      </label>
    </p>

    <p>
      <label for="wp-cleanfix-tools-posts-replace">
        <?php esc_attr_e('Replace', 'wp-cleanfix'); ?>
        <textarea name="wp-cleanfix-tools-posts-replace"
                  id="wp-cleanfix-tools-posts-replace"></textarea>
      </label>
    </p>

    <p class="clearfix">
      <button class="button button-secondary wp-clearfix-tools-posts-clear-fields alignleft">
        <?php esc_attr_e('Clear Fields', 'wp-cleanfix'); ?>
      </button>
      <button class="button button-primary wp-clearfix-tools-posts-find-button alignright">
        <?php esc_attr_e('Find & Replace', 'wp-cleanfix'); ?>
      </button>
    </p>

    <div id="wp-clearfix-tools-posts-feedback"></div>

  </form>
</div>

<?php WPCleanFix\PureCSSTabs\PureCSSTabsProvider::closeTab();
