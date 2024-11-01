<?php if (!defined('ABSPATH')) {
  exit();
} ?>

<div class="wp-cleanfix-tools wrap">

  <h1><?php esc_attr_e('WP CleanFix Tools', 'wp-cleanfix'); ?></h1>

  <?php if (isset($feedback)): ?>

    <div id="message"
         class="updated notice is-dismissible">
      <p><?php echo esc_attr($feedback); ?></p>
    </div>

  <?php endif; ?>

  <div class="wpbones-tabs">

    <?php WPCleanFix()
      ->view('tools.database')
      ->with('database', $database)
      ->render(); ?>

    <?php WPCleanFix()->view('tools.posts')->with('posts', $posts)->render(); ?>

    <?php WPCleanFix()
      ->view('tools.comments')
      ->with('comments', $comments)
      ->render(); ?>

    <?php WPCleanFix()
      ->view('tools.postmeta')
      ->with('postmeta', $postmeta)
      ->render(); ?>

  </div>

</div>
