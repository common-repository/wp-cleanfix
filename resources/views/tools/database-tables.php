<?php if (!defined('ABSPATH')) {
  exit();
} ?>

<table class="wp-cleanfix-tools-database" width="100%" border="0" cellpadding="0" cellspacing="0">
  <thead>
  <tr>
    <th><?php esc_attr_e('Table', 'wp-cleanfix'); ?></th>
    <th><?php esc_attr_e('Auto Increment', 'wp-cleanfix'); ?></th>
    <th><?php esc_attr_e('Action', 'wp-cleanfix'); ?></th>
  </tr>
  </thead>

  <tbody>
  <?php foreach ($database->tables as $table => $label): ?>
    <?php $info = $database->getTableInformation($table); ?>

    <tr>
      <td>
        <?php echo esc_attr($label); ?>
      </td>

      <td data-table="auto-increment-<?php echo esc_attr($table); ?>">
        <?php 
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $info->Auto_increment; 
        ?>
      </td>

      <td>
        <button class="button button-primary wp-cleanfix-button-compact"
                data-table="<?php echo esc_attr($table); ?>">
          <?php esc_html_e('Compact', 'wp-cleanfix'); ?>
        </button>
      </td>

    </tr>

  <?php endforeach; ?>

  </tbody>

</table>
