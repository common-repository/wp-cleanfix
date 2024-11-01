<?php

function NoMatchStringFound()
{
  ?>
  <p><?php esc_attr_e('No matching strings found!', 'wp-cleanfix'); ?></p>
  <?php
}

function ContinueCancelButton($context)
{
  ?>
  <p>
    <button href="#" data-confirm="<?php esc_attr_e(
      'Are you sure to replace the strings? This operation is irreversible!',
      'wp-cleanfix'
    ); ?>" class="button button-primary wp-cleanfix-tools-<?php echo esc_attr($context); ?>-replace-button">
        <?php esc_attr_e('Continue ?', 'wp-cleanfix'); ?>
    </button>

    <button href="#" class="button button-secondary wp-cleanfix-tools-<?php echo esc_attr(
      $context
    ); ?>-replace-cancel-button"><?php esc_attr_e('Cancel', 'wp-cleanfix'); ?></button>
  </p>
  <?php
}

function OkButton($context)
{
  ?>
<button href="#" class="button button-hero button-primary wp-cleanfix-tools-<?php echo esc_attr(
  $context
); ?>-ok-button">
  <?php esc_attr_e('Ok', 'wp-cleanfix'); ?>
</button>
<?php
}

function FindReplaceFeedback($total, $found, $affected = null)
{
  ?>
<p>
  <?php printf(wp_kses_data($total[0]), esc_attr($total[1])); ?>,
  <?php printf(wp_kses_data($found[0]), esc_attr($found[1])); ?>
  <?php !is_null($affected) &&
    printf(wp_kses_data($affected[0]), esc_attr($affected[1])); ?>
</p>
<?php
}
