<?php if (!defined('ABSPATH')) {
  exit();
} ?>

<div class="wp-cleanfix-settings wrap">

  <h1><?php esc_attr_e('WP CleanFix Settings', 'wp-cleanfix'); ?></h1>

  <?php if (isset($feedback)): ?>

    <div id="message"
         class="updated notice is-dismissible"><p><?php echo esc_attr(
           $feedback
         ); ?></p></div>

  <?php endif; ?>

  <div class="wpbones-tabs">
    <?php echo esc_attr(
      WPCleanFix()->view('settings.database')->with('plugin', $plugin)
    ); ?>
    <?php echo esc_attr(
      WPCleanFix()->view('settings.options')->with('plugin', $plugin)
    ); ?>
  </div>

</div>
