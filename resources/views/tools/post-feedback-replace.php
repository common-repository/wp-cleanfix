<?php if (!defined('ABSPATH')) {
  exit();
}

include_once 'components.php';

if (empty($count['count'])): ?>

  <div>
     <?php NoMatchStringFound(); ?>
  </div>

<?php else: ?>

  <div class="wp-cleanfix-tools-posts-feedback-after-replace">

    <?php FindReplaceFeedback(
      [
        // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
        _n(
          'Searched in <strong>%s</strong> post only',
          'Searched in <strong>%s</strong> posts',
          $count['total_posts'],
          'wp-cleanfix'
        ),
        $count['total_posts'],
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
          'in <strong>%s</strong> post only',
          'in <strong>%s</strong> posts',
          $count['affected_posts'],
          'wp-cleanfix'
        ),
        $count['affected_posts'],
      ]
    ); ?>

  <?php OkButton('posts'); ?>
  </div>

<?php endif;
