<?php if (!defined('ABSPATH')) {
  exit();
}

include_once 'components.php';

if (empty($count['affected_postmeta'])): ?>

  <div>
     <?php NoMatchStringFound(); ?>
  </div>

<?php else: ?>
<div class="wp-cleanfix-tools-postmeta-feedback-found">

  <?php
  FindReplaceFeedback(
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
        'only <strong>%s</strong> matched will be replaced',
        'the <strong>%s</strong> matches will be replaced',
        number_format_i18n($count['affected_postmeta']),
        'wp-cleanfix'
      ),
      $count['affected_postmeta'],
    ]
  );

  ContinueCancelButton('postmeta');
  ?>
  </div>

<?php endif;
