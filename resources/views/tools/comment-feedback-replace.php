<?php if (!defined('ABSPATH')) {
  exit();
}

include_once 'components.php';

if (empty($count['count'])): ?>

  <div>
     <?php NoMatchStringFound(); ?>
  </div>

<?php else: ?>

  <div class="wp-cleanfix-tools-comments-feedback-after-replace">

  <?php FindReplaceFeedback(
    [
      // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
      _n(
        'Searched in <strong>%s</strong> comment only',
        'Searched in <strong>%s</strong> comments',
        $count['total_comments'],
        'wp-cleanfix'
      ),
      $count['total_comments'],
    ],
    [
      // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
      _n(
        'Replaced <strong>%s</strong> string only',
        'Replaced <strong>%s</strong> strings',
        $count['count'],
        'wp-cleanfix'
      ),
      $count['count'],
    ],
    [
      // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
      _n(
        'in <strong>%s</strong> comment only',
        'in <strong>%s</strong> comments',
        $count['affected_comments'],
        'wp-cleanfix'
      ),
      $count['affected_comments'],
    ]
  ); ?>

  <?php OkButton('comments'); ?>
  </div>

<?php endif;
