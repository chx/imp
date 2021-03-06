<?php

/**
 * @file
 * Contains \Drupal\migrate\Tests\FakeStatement.
 */

namespace Drupal\migrate\Tests;

use Drupal\Core\Database\StatementInterface;

class FakeStatement extends \ArrayIterator implements StatementInterface {

  public function execute($args = array(), $options = array()) {
    throw new \Exception('This method is not supported');
  }

  public function getQueryString() {
    throw new \Exception('This method is not supported');
  }

  public function rowCount() {
    return $this->count();
  }

  public function fetchField($index = 0) {
    $row = array_values($this->current());
    $return = $row[$index];
    $this->next();
    return $return;
  }

  public function fetchAssoc() {
    $return = $this->current();
    $this->next();
    return $return;
  }

  public function fetchCol($index = 0) {
    $return = array();
    foreach ($this as $row) {
      $row = array_values($row);
      $return[] = $row[$index];
    }
    return $return;
  }

  public function fetchAllKeyed($key_index = 0, $value_index = 1) {
    $return = array();
    foreach ($this as $row) {
      $row = array_values($row);
      $return[$row[$key_index]] = $row[$value_index];
    }
    return $return;
  }

  public function fetchAllAssoc($key, $fetch = NULL) {
    $return = array();
    foreach ($this as $row) {
      $return[$row[$key]] = $row;
    }
    return $return;
  }

  /**
   * Provide meta information about this battery of tests.
   */
  public static function getInfo() {
    return array(
      'name' => 'Fake statement test',
      'description' => 'Tests for fake statement plugin.',
      'group' => 'Migrate',
    );
  }

}
