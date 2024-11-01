<?php if (!defined('ABSPATH')) {
  exit();
} ?>

<tr class="wp-cleanfix-bottom-row">
  <td colspan="6">
    <div class="wpxcf-table-more-info">

      <div class="wpxcf-table-more-display-scroll">
        <table class="wpxcf-table-more-info-header">
          <thead>
          <tr>
            <th class="wpxcf-table-more-info-column-engine"><?php esc_attr_e(
              'Engine',
              'wp-cleanfix'
            ); ?></th>
            <th class="wpxcf-table-more-info-column-name"><?php esc_attr_e(
              'Name',
              'wp-cleanfix'
            ); ?></th>
            <th class="wpxcf-table-more-info-column-auto-increment"><?php esc_attr_e(
              'Auto Increment',
              'wp-cleanfix'
            ); ?></th>
            <th class="wpxcf-table-more-info-column-gain"><?php esc_attr_e(
              'Gain',
              'wp-cleanfix'
            ); ?></th>
          </tr>
          </thead>
        </table>
      </div>

      <div class="wpxcf-table-more-info-content">
        <table class="wpxcf-table-more-info-body">

          <tbody>
          <?php foreach (
            $module->DatabaseTablesTest->tables()
            as $table_name => $info
          ): ?>
            <tr class="<?php echo $info['optimize'] ? 'optimize' : ''; ?>">
              <td class="wpxcf-table-more-info-column-engine"><?php echo esc_attr(
                $info['engine']
              ); ?></td>
              <td class="wpxcf-table-more-info-column-name"><?php echo esc_attr(
                $table_name
              ); ?></td>
              <td class="wpxcf-table-more-info-column-auto-increment"><?php echo esc_attr(
                $info['auto_increment']
              ); ?></td>
              <td class="wpxcf-table-more-info-column-gain"><?php echo esc_attr(
                $info['gain']
              ); ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>

        </table>
      </div>

      <div class="wpxcf-table-more-display-scroll">
        <table class="wpxcf-table-more-info-footer">
          <tfoot>
          <tr>
            <td><?php esc_attr_e('Total Gain', 'wp-cleanfix'); ?></td>
            <td><?php printf(
              '%6.2f Kb',
              esc_attr($module->DatabaseTablesTest->totalGain())
            ); ?></td>
          </tr>
          </tfoot>
        </table>
      </div>

    </div>
  </td>
</tr>
