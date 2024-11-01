<?php if (!defined('ABSPATH')) {
  exit();
}

include_once 'components.php';

if (empty($count['affected_postmeta'])): ?>

  <div>
    <?php NoMatchStringFound(); ?>
  </div>

<?php else: ?>

  <div class="wp-cleanfix-tools-postmeta-feedback-after-replace">

  <?php FindReplaceFeedback(
    [
      // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
      _n(
        'Searched in <strong>%s</strong> postmeta only',
        'Searched in <strong>%s</strong> postmeta',
        number_format_i18n($count['count']),
        'wp-cleanfix'
      ),
      $count['count'],
    ],
    [
      // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
      _n(
        'Replaced <strong>%s</strong> string only',
        'Replaced <strong>%s</strong> strings',
        $count['affected_postmeta'],
        'wp-cleanfix'
      ),
      $count['affected_postmeta'],
    ]
  ); ?>

  <?php OkButton('postmeta'); ?>
  </div>

<?php endif;
