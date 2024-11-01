<?php

namespace WPCleanFix\Modules\Posts;

use WPCleanFix\Modules\Module;

class PostsModule extends Module
{
  protected $view = 'module.index';

  protected $tests = [
    'WPCleanFix\Modules\Posts\AutodraftTest',
    'WPCleanFix\Modules\Posts\RevisionsTest',
    'WPCleanFix\Modules\Posts\PostsWithoutUserTest',
    'WPCleanFix\Modules\Posts\OrphanPostMetaTest',
    'WPCleanFix\Modules\Posts\OrphanAttachmentsTest',
    'WPCleanFix\Modules\Posts\OrphanPostTypesTest',
    'WPCleanFix\Modules\Posts\TemporaryTest',
    'WPCleanFix\Modules\Posts\TrashTest',
  ];

  public function getMetaBoxTitle()
  {
    return __('Posts, Pages and Custom Post Types', 'wp-cleanfix');
  }

  /*
  |--------------------------------------------------------------------------
  | Module methods
  |--------------------------------------------------------------------------
  |
  | Here you'll find the module methods used by single test.
  |
  */

  public function getPostsWithStatus($status = 'auto-draft')
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT DISTINCT( COUNT(*) ) AS number, post_title
    FROM $wpdb->posts
    WHERE post_status = %s
    GROUP BY post_title
    ORDER BY post_title",
        $status
      )
    );
  }

  public function deletePostsWithStatus($status = 'auto-draft')
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $posts = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT ID
    FROM $wpdb->posts
    WHERE post_status = %s",
        $status
      )
    );

    foreach ($posts as $post) {
      wp_delete_post($post->ID, true);
    }
  }

  public function getPostsWithType($type = 'revision')
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT DISTINCT( COUNT(*) ) AS number, post_title
    FROM $wpdb->posts
    WHERE post_type = %s
    GROUP BY post_title
    ORDER BY post_title",
        $type
      )
    );
  }

  public function deletePostsWithType($type = 'revision')
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $posts = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT ID
    FROM $wpdb->posts
    WHERE post_type = %s",
        $type
      )
    );

    foreach ($posts as $post) {
      wp_delete_post($post->ID, true);
    }
  }

  public function getPostsWithoutUser()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $post_status = 'inherit';
    $post_type = 'wp_navigation';

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT posts.post_title, posts.ID AS post_id FROM $wpdb->posts AS posts
      LEFT JOIN $wpdb->users AS users ON ( users.ID = posts.post_author )
      WHERE 1
      AND users.ID IS NULL
      AND posts.ID IS NOT NULL
      AND posts.post_type <> %s
      AND posts.post_status <> %s
    ",
        $post_type,
        $post_status
      )
    );
  }

  public function updatePostsWithoutUser($user_id)
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $posts = $posts = $this->getPostsWithoutUser();

    if (!empty($posts)) {
      $stack = [];
      foreach ($posts as $post) {
        $stack[] = $post->post_id;
      }

      $in_ids = '(' . implode(',', $stack) . ')';

      // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
      $wpdb->query(
        $wpdb->prepare(
          "UPDATE $wpdb->posts SET post_author = %d WHERE ID IN",
          $user_id
        ) . $in_ids
      );
    }
  }

  public function getPostMetaWithoutPosts()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results("SELECT DISTINCT( COUNT(*) ) AS number, post_meta.meta_id, post_meta.meta_key FROM $wpdb->postmeta AS post_meta
    LEFT JOIN {$wpdb->posts} posts ON ( posts.ID = post_meta.post_id )
    WHERE posts.ID IS NULL
    GROUP BY post_meta.meta_key
    ORDER BY post_meta.meta_key");
  }

  public function deletePostMetaWithoutPosts()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query("DELETE post_meta FROM $wpdb->postmeta AS post_meta
    LEFT JOIN $wpdb->posts AS posts ON ( posts.ID = post_meta.post_id )
    WHERE posts.ID IS NULL");
  }

  public function getAttachmentsWithNullPost()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Put in cache transient.
    $cache = get_transient('wp-cleanfix-posts_attachments');

    if (empty($cache)) {
      // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
      $cache = $wpdb->get_results("SELECT posts_attachment.post_title, posts_attachment.ID as attachment_id FROM $wpdb->posts AS posts_attachment
      LEFT JOIN $wpdb->posts AS posts ON ( posts_attachment.post_parent = posts.ID )
      WHERE 1
      AND posts_attachment.post_type = 'attachment'
      AND posts_attachment.post_parent > 0
      AND posts.ID IS NULL");

      set_transient('wp-cleanfix-posts_attachments', $cache, 60 * 60);
    }

    return $cache;
  }

  public function deleteAttachmentsWithNullPost()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Get from cache transient.
    $cache = get_transient('wp-cleanfix-posts_attachments');

    if (empty($cache)) {
      $cache = $this->getAttachmentsWithNullPost();
      set_transient('wp-cleanfix-posts_attachments', $cache, 60 * 60);
    }

    $stack = [];
    foreach ($cache as $attachment) {
      $stack[] = $attachment->attachment_id;
    }
    $in_ids = '(' . implode(',', $stack) . ')';

    delete_transient('wpxcf-posts_attachments');

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query(
      $wpdb->prepare(
        "UPDATE $wpdb->posts SET post_parent = %d WHERE ID IN ",
        0
      ) . $in_ids
    );
  }

  public function getTemporaryPostMeta()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results("SELECT DISTINCT( COUNT(*) ) AS number, post_meta.meta_id, post_meta.meta_key FROM $wpdb->postmeta post_meta
    LEFT JOIN $wpdb->posts posts ON posts.ID = post_meta.post_id
    WHERE posts.ID IS NOT NULL
    AND (
       post_meta.meta_key = '_edit_lock'
    OR post_meta.meta_key = '_edit_last'
    OR post_meta.meta_key = '_wp_old_slug'
       )
    GROUP BY post_meta.meta_key
    ORDER BY post_meta.meta_key");
  }

  public function deleteTemporaryPostMeta()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query("DELETE post_meta FROM $wpdb->postmeta AS post_meta
    LEFT JOIN $wpdb->posts posts ON ( posts.ID = post_meta.post_id )
    WHERE 1
    AND posts.ID IS NOT NULL
    AND (
       post_meta.meta_key = '_edit_lock'
    OR post_meta.meta_key = '_edit_last'
    OR post_meta.meta_key = '_wp_old_slug')");
  }

  public function getUnregisteredPostTypes()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // get the registered post types
    $registeredPostTypes = array_keys(get_post_types());

    $array = [];

    foreach ($registeredPostTypes as $key) {
      $array[] = "'{$key}'";
    }

    $post_types_str = '(' . implode(',', $array) . ')';

    $group_by = 'GROUP BY posts.post_type';

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT COUNT( posts.post_type ) AS number, posts.post_type
    FROM $wpdb->posts AS posts
    WHERE %d AND posts.post_type NOT IN",
        1
      ) .
        $post_types_str .
        $group_by
    );
  }

  public function deleteUnregisteredPostTypes()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // get the registered post types
    $registeredPostTypes = array_keys(get_post_types());

    $array = [];

    foreach ($registeredPostTypes as $key) {
      $array[] = "'{$key}'";
    }

    $in_post_types_str = '(' . implode(',', $array) . ')';

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query(
      $wpdb->prepare(
        "DELETE FROM $wpdb->posts WHERE %d AND post_type NOT IN ",1
      ) . $in_post_types_str
    );
  }
}
