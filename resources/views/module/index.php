<?php if (!defined('ABSPATH')) {
  exit();
} ?>

<div class="wp-cleanfix-module">
  <?php $module->toolBar(); ?>
  <table class="wp-cleanfix-table-module">
    <tbody>
    <?php foreach ($module->tests as $test): ?>

      <?php $test->renderHtmlRow(); ?>

    <?php endforeach; ?>

    </tbody>
  </table>

</div>
