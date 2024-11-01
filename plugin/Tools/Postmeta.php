<?php

namespace WPCleanFix\Tools;

if (!defined('ABSPATH')) {
  exit();
}

class Postmeta
{
  public function __get($name)
  {
    $method_name = 'get' . ucfirst($name) . 'Attribute';

    if (method_exists($this, $method_name)) {
      return call_user_func([$this, $method_name]);
    }
  }

  public function getCount($column = 'meta_key', $find = '', $replace = '')
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $count = 0;

    if (empty($find) || $find == $replace) {
      return ['count' => $count];
    }

    if (!in_array($column, ['meta_key', 'meta_value'])) {
      return ['count' => $count];
    }

    if ($column == 'meta_key' && empty($replace)) {
      return ['count' => $count];
    }

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $row = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT COUNT(*) AS total_postmeta, ( SELECT COUNT(*) FROM $wpdb->postmeta WHERE %i = %s ) AS affected_postmeta FROM $wpdb->postmeta",
        [$column, $find]
      )
    );

    return [
      'count' => $row->total_postmeta,
      'affected_postmeta' => is_null($row->affected_postmeta)
        ? 0
        : $row->affected_postmeta,
    ];
  }

  public function replace($column = 'meta_key', $find = '', $replace = '')
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    if (empty($find) || $find == $replace) {
      return false;
    }

    if (!in_array($column, ['meta_key', 'meta_value'])) {
      return false;
    }

    if ($column == 'meta_key' && empty($replace)) {
      return false;
    }

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $result = $wpdb->update(
      $wpdb->postmeta,
      [$column => $replace],
      [$column => $find]
    );

    return $result;
  }
}
