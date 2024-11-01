<?php if (!defined('ABSPATH')) {
  exit();
}

WPCleanFix\PureCSSTabs\PureCSSTabsProvider::openTab(
  __('Comments', 'wp-cleanfix')
);
?>

<div class="wp-cleanfix-tools-comments-find-replace">
  <form>
    <h3><?php esc_attr_e('Find & Replace', 'wp-cleanfix'); ?></h3>

    <div class="wp-cleanfix-info">
      <?php echo wp_kses_data(
        __(
          'This tool will perform a search for a text string within the <strong>comments content</strong> and will replace it with another text. <strong>Be careful because this operation is irreversible.</strong>',
          'wp-cleanfix'
        )
      ); ?>
    </div>

    <p>
      <label for="wp-cleanfix-tools-comments-approved">
        <?php esc_attr_e('Comments approved', 'wp-cleanfix'); ?>
        <select name="wp-cleanfix-tools-comments-approved"
                id="wp-cleanfix-tools-comments-approved">
          <?php foreach ($comments->getCommentsApproved() as $key => $value): ?>
            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr(
  $value
); ?></option>
          <?php endforeach; ?>
        </select>
      </label>
    </p>

    <p>
      <label for="wp-cleanfix-tools-comments-type">
        <?php esc_attr_e('Comment type', 'wp-cleanfix'); ?>
        <select name="wp-cleanfix-tools-comments-type"
                id="wp-cleanfix-tools-comments-type">
          <option value=""><?php esc_attr_e('All'); ?></option>
          <?php foreach ($comments->getCommentsType() as $key => $value): ?>
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
      <label for="wp-cleanfix-tools-comments-find">
        <?php esc_attr_e('Find', 'wp-cleanfix'); ?>
        <textarea name="wp-cleanfix-tools-comments-find"
                  id="wp-cleanfix-tools-comments-find"></textarea>
      </label>
    </p>

    <p>
      <label for="wp-cleanfix-tools-comments-replace">
        <?php esc_attr_e('Replace', 'wp-cleanfix'); ?>
        <textarea name="wp-cleanfix-tools-comments-replace"
                  id="wp-cleanfix-tools-comments-replace"></textarea>
      </label>
    </p>

    <p class="clearfix">
      <button class="button button-secondary wp-clearfix-tools-comments-clear-fields alignleft">
        <?php esc_attr_e('Clear Fields', 'wp-cleanfix'); ?>
      </button>
      <button class="button button-primary wp-clearfix-tools-comments-find-button alignright">
        <?php esc_attr_e('Find & Replace', 'wp-cleanfix'); ?>
      </button>
    </p>

    <div id="wp-clearfix-tools-comments-feedback"></div>

  </form>
</div>

<?php WPCleanFix\PureCSSTabs\PureCSSTabsProvider::closeTab();
