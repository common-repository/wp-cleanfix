<?php if (!defined('ABSPATH')) {
    exit();
}

WPCleanFix\PureCSSTabs\PureCSSTabsProvider::openTab(
    __('Options', 'wp-cleanfix')
);
?>

  <form action=""
        method="post">

    <?php wp_nonce_field('wp_cleanfix'); ?>

    <p>
      <label for="Options/expiry_date">
        <?php esc_attr_e('Expiry date', 'wp-cleanfix'); ?>:
        <select name="Options/expiry_date"
                id="Options/expiry_date">
          <option <?php selected(
              '0',
              $plugin->options->get('Options.expiry_date')
          ); ?> value="0"><?php esc_attr_e(
              'Today',
              'wp-cleanfix'
          ); ?></option>
          <option <?php selected(
              DAY_IN_SECONDS,
              $plugin->options->get('Options.expiry_date')
          ); ?> value="<?php echo esc_attr(
              DAY_IN_SECONDS
          ); ?>"><?php esc_attr_e('Daily', 'wp-cleanfix'); ?></option>
          <option <?php selected(
              WEEK_IN_SECONDS,
              $plugin->options->get('Options.expiry_date')
          ); ?> value="<?php echo esc_attr(
              WEEK_IN_SECONDS
          ); ?>"><?php esc_attr_e('Weekly', 'wp-cleanfix'); ?></option>
        </select>
      </label>
    </p>

    <p>
      <?php WPCleanFix\PureCSSSwitch\Html\HtmlTagSwitchButton::name(
          'Options/safe_mode'
      )
        ->checked($plugin->options->get('Options/safe_mode'))
        ->right_label(__('Safe mode', 'wp-cleanfix'))
        ->render(); ?>
    </p>

    <div class="wp-cleanfix-info">
      <?php esc_attr_e(
          'We will use the WordPress function <code>get_transient()</code> to remove the expired transients.',
          'wp-cleanfix'
      ); ?>
    </div>

    <p class="clearfix">
      <button class="button button-primary alignright">Update</button>
    </p>

  </form>

<?php WPCleanFix\PureCSSTabs\PureCSSTabsProvider::closeTab();
