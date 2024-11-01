<?php

namespace WPCleanFix\Modules\Taxonomies;

use WPCleanFix\Modules\Module;

class TaxonomiesModule extends Module
{
  protected $view = 'module.index';

  protected $tests = [
    'WPCleanFix\Modules\Taxonomies\ConsistentTermsTest',
    'WPCleanFix\Modules\Taxonomies\ConsistentTermsTaxonomiesTest',
    'WPCleanFix\Modules\Taxonomies\ConsistentTermsRelationshipsTest',
    'WPCleanFix\Modules\Taxonomies\OrphanPostTagsTest',
    'WPCleanFix\Modules\Taxonomies\OrphanPostCategoriesTest',
    'WPCleanFix\Modules\Taxonomies\OrphanTermsTest',
  ];

  public function getMetaBoxTitle()
  {
    return __('Terms and Taxonomies', 'wp-cleanfix');
  }

  /*
  |--------------------------------------------------------------------------
  | Module methods
  |--------------------------------------------------------------------------
  |
  | Here you'll find the module methods used by single test.
  |
  */

  public function getOrphanTerms()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results("SELECT * FROM ( $wpdb->terms AS T )
    LEFT JOIN $wpdb->term_taxonomy AS TT ON ( T.term_id = TT.term_id )
    LEFT JOIN $wpdb->term_relationships AS TR ON TR.term_taxonomy_id = TT.term_taxonomy_id
    WHERE ( TT.taxonomy <> 'category' AND TT.taxonomy <> 'post_tag' )
    AND T.term_id <> 1
    AND TT.count = 0
    AND TR.term_taxonomy_id IS NULL
    AND ( SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE parent = T.term_id ) = 0
    ORDER BY T.name");
  }

  public function deleteOrphanTerms()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $items = $wpdb->get_results("SELECT
     T.term_id AS t_term_id,
     TT.term_taxonomy_id AS tt_term_taxonomy_id FROM ( $wpdb->terms AS T )
    LEFT JOIN $wpdb->term_taxonomy AS TT ON ( T.term_id = TT.term_id )
    LEFT JOIN $wpdb->term_relationships AS TR ON TR.term_taxonomy_id = TT.term_taxonomy_id
    WHERE ( TT.taxonomy <> 'category' AND TT.taxonomy <> 'post_tag' )
    AND T.term_id <> 1
    AND TT.count = 0
    AND TR.term_taxonomy_id IS NULL
    AND ( SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE parent = T.term_id ) = 0
    ORDER BY T.name");

    $t_term_id = [];
    $tt_term_taxonomy_id = [];

    foreach ($items as $value) {
      $t_term_id[] = $value->t_term_id;
      $tt_term_taxonomy_id[] = $value->tt_term_taxonomy_id;
    }

    $in_terms = '(' . implode(',', $t_term_id) . ')';

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query(
      $wpdb->prepare("DELETE FROM $wpdb->terms WHERE %d AND term_id IN ", 1) .
        $in_terms
    );

    $in_term_taxonomy = '(' . implode(',', $tt_term_taxonomy_id) . ')';

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query(
      $wpdb->prepare(
        "DELETE FROM $wpdb->term_taxonomy WHERE %d AND term_taxonomy_id IN ",
        1
      ) . $in_term_taxonomy
    );
  }

  public function getOrphanPostCategories()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results("SELECT *

    FROM {$wpdb->terms} AS T
    LEFT JOIN {$wpdb->term_taxonomy} AS TT ON T.term_id = TT.term_id
    LEFT JOIN {$wpdb->term_relationships} AS TR ON TR.term_taxonomy_id = TT.term_taxonomy_id

    WHERE TT.taxonomy = 'category'

    AND T.term_id <> 1
    AND TT.count = 0
    AND TR.term_taxonomy_id IS NULL
    AND ( SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE parent = T.term_id ) = 0

    ORDER BY T.name");
  }

  public function deleteOrphanPostCategories()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $items = $wpdb->get_results("SELECT

     T.term_id AS t_term_id,
     TT.term_taxonomy_id AS tt_term_taxonomy_id

    FROM {$wpdb->terms} AS T

    LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( T.term_id = TT.term_id )
    LEFT JOIN {$wpdb->term_relationships} AS TR ON ( TR.term_taxonomy_id = TT.term_taxonomy_id )

    WHERE TT.taxonomy = 'category'

    AND T.term_id <> 1
    AND TT.count = 0
    AND TR.term_taxonomy_id IS NULL
    AND ( SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE parent = T.term_id ) = 0");

    $t_term_id = [];
    $tt_term_taxonomy_id = [];

    foreach ($items as $value) {
      $t_term_id[] = $value->t_term_id;
      $tt_term_taxonomy_id[] = $value->tt_term_taxonomy_id;
    }

    $in_terms = '(' . implode(',', $t_term_id) . ')';

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query(
      $wpdb->prepare("DELETE FROM $wpdb->terms WHERE %d AND term_id IN ", 1) .
        $in_terms
    );

    $in_term_taxonomy = '(' . implode(',', $tt_term_taxonomy_id) . ')';

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query(
      $wpdb->prepare(
        "DELETE FROM $wpdb->term_taxonomy WHERE %d AND term_taxonomy_id IN ",
        1
      ) . $in_term_taxonomy
    );
  }

  public function getOrphanPostTags()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results("SELECT *

    FROM ( {$wpdb->terms} AS T )

    INNER JOIN {$wpdb->term_taxonomy} AS TT ON ( T.term_id = TT.term_id )
    LEFT JOIN {$wpdb->term_relationships} AS TR ON ( TR.term_taxonomy_id = TT.term_taxonomy_id )

    WHERE 1

    AND TT.taxonomy = 'post_tag'
    AND TT.count = 0
    AND TR.object_id IS NULL

    ORDER BY T.name");
  }

  public function deleteOrphanPostTags()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $results = $wpdb->get_results(
      "SELECT T.term_id

      FROM {$wpdb->terms} AS T

      INNER JOIN {$wpdb->term_taxonomy} AS TT ON ( T.term_id = TT.term_id )
      LEFT JOIN {$wpdb->term_relationships} AS TR ON ( TR.term_taxonomy_id = TT.term_taxonomy_id )

      WHERE 1

      AND TT.taxonomy = 'post_tag'
      AND TT.count = 0
      AND TR.object_id IS NULL",
      OBJECT_K
    );

    if (empty($results)) {
      return;
    }

    $keys = '(' . implode(',', array_keys($results)) . ')';

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query(
      $wpdb->prepare("DELETE FROM $wpdb->terms WHERE %d AND term_id IN ", 1) .
        $keys
    );

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query(
      $wpdb->prepare(
        "DELETE FROM $wpdb->term_taxonomy WHERE %d AND term_id IN ",
        1
      ) . $keys
    );

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query(
      "DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id NOT IN ( SELECT term_taxonomy_id FROM $wpdb->term_taxonomy )"
    );
  }

  public function getConsistentTermsRelationships()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $result = $wpdb->get_results("SELECT *
    FROM {$wpdb->term_relationships} AS TR
    LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( TT.term_taxonomy_id = TR.term_taxonomy_id )
    LEFT JOIN {$wpdb->terms} AS T ON ( TT.term_id = T.term_id )
    LEFT JOIN {$wpdb->posts} AS P ON ( P.ID = TR.object_id )
    WHERE 1
    AND TT.term_taxonomy_id IS NULL
    OR P.ID IS NULL");

    return $result;
  }

  public function deleteConsistentTermsRelationships()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query("DELETE TR
    FROM {$wpdb->term_relationships} AS TR
    LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( TT.term_taxonomy_id = TR.term_taxonomy_id )
    LEFT JOIN {$wpdb->posts} AS P ON ( P.ID = TR.object_id )
    WHERE 1
    AND TT.term_taxonomy_id IS NULL
    OR P.ID IS NULL");
  }

  public function getConsistentTermsTaxonomies()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $result = $wpdb->get_results("SELECT *
    FROM {$wpdb->term_taxonomy} AS TT
    LEFT JOIN {$wpdb->terms} AS T ON ( T.term_id = TT.term_id )
    WHERE 1
    AND TT.term_id <> 1
    AND T.term_id IS NULL
    ORDER BY TT.taxonomy");

    return $result;
  }

  public function deleteConsistentTermsTaxonomies()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query("DELETE TT FROM {$wpdb->term_taxonomy} AS TT
    LEFT JOIN {$wpdb->terms} AS T ON ( T.term_id = TT.term_id )
    WHERE 1
    AND TT.term_id <> 1
    AND T.term_id IS NULL");
  }

  public function getConsistentTerms()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    return $wpdb->get_results("SELECT *
    FROM {$wpdb->terms} AS T
    LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( TT.term_id = T.term_id )
    WHERE 1
    AND T.term_id <> 1
    AND TT.term_taxonomy_id IS NULL
    ORDER BY T.name");
  }

  public function deleteConsistentTerms()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query("DELETE T
    FROM {$wpdb->terms} AS T
    LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( TT.term_id = T.term_id )
    WHERE 1
    AND T.term_id <> 1
    AND TT.term_taxonomy_id IS NULL");
  }
}
