<?php

namespace WPCleanFix\Modules\Posts;

use WPCleanFix\Modules\Test;

class PostsWithoutUserTest extends Test
{
  public function test()
  {
    // for this method see parent module
    $issues = $this->getPostsWithoutUser();

    $this->issues($issues)
      ->detailSelect(
        sprintf(
          // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
          _n(
            'You have %s post not correctly assigned to any author',
            'You have %s posts not correctly assigned to any author',
            count($issues),
            'wp-cleanfix'
          ),
          count($issues)
        ),
        [
          'post_title' => '%s',
        ],
        $this->getUsersSelect()
      )
      ->beforeSend('wp_cleanfix_user_before_send')
      ->filter('wp_cleanfix_user_id')
      ->fix(
        __('Fix: click here to repair posts without authors.', 'wp-cleanfix')
      );

    return $this;
  }

  protected function getUsersSelect()
  {
    ob_start(); ?>

    <select name="wp-cleanfix-user">
      <option selected
              disabled="disabled"
              style="display:none"><?php esc_attr_e(
                'Choose a new user...',
                'wp-cleanfix'
              ); ?></option>
      <?php foreach (get_users() as $user): ?>
        <option value="<?php echo esc_attr($user->ID); ?>"><?php printf(
  '%s (%s)',
  esc_attr($user->display_name),
  esc_attr($user->user_email)
); ?></option>
      <?php endforeach; ?>
    </select>
  
    <?php
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
  }

  public function cleanFix()
  {
    // TODO: Implement fix() method.

    if (func_num_args()) {
      $args = func_get_arg(0);
      $user_id = $args['user_id'];
      $this->updatePostsWithoutUser($user_id);
    }

    // for this method see parent module

    return $this;
  }

  public function getName()
  {
    return __('Posts without author', 'wp-cleanfix');
  }

  public function getDescription()
  {
    return __('Posts without author.', 'wp-cleanfix');
  }
}
