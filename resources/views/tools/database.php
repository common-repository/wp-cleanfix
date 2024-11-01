<?php if (!defined('ABSPATH')) {
  exit();
}

WPCleanFix\PureCSSTabs\PureCSSTabsProvider::openTab(
  __('Compact index', 'wp-cleanfix'),
  null,
  true
);
?>

<div>
  <form>
    <h3><?php esc_attr_e('Compact index', 'wp-cleanfix'); ?></h3>

    <div class="wp-cleanfix-info">
      <?php esc_attr_e(
        'This tool will perform a datbase table index compact on the following tables.',
        'wp-cleanfix'
      ); ?>
    </div>

    <p>
      <?php WPCleanFix\PureCSSSwitch\Html\HtmlTagSwitchButton::name(
        'wp-cleanfix-tools-database-make-backup'
      )
        ->checked(true)
        ->right_label(__('Make a backup copy before compact', 'wp-cleanfix'))
        ->render(); ?>
    </p>

    <?php WPCleanFix()
      ->view('tools.database-tables')
      ->with('database', $database)
      ->render(); ?>

  </form>

</div>

<?php WPCleanFix\PureCSSTabs\PureCSSTabsProvider::closeTab();
