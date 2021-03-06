<?php

/**
 * @file
 * Contains \Drupal\system\Tests\Upgrade\MigrateTestBase.
 */

namespace Drupal\migrate\Tests;

use Drupal\Core\Database\Database;
use Drupal\migrate\Entity\MigrationInterface;
use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\simpletest\WebTestBase;

class MigrateTestBase extends WebTestBase {

  /**
   * The file path(s) to the dumped database(s) to load into the child site.
   *
   * @var array
   */
  var $databaseDumpFiles = array();

  public static $modules = array('migrate');

  protected function prepare(MigrationInterface $migration, array $files = array()) {
    $databasePrefix = 'simpletest_m_' . mt_rand(1000, 1000000);
    $connection_info = Database::getConnectionInfo('default');
    $connection_info['default']['prefix']['default'] .= $databasePrefix;
    $database = SqlBase::getDatabaseConnection($migration->id(), array('database' => $connection_info['default']));
    foreach (array('source', 'destination', 'id_map') as $key) {
      $configuration = $migration->get($key);
      $configuration['database'] = $database;
      $migration->set($key, $configuration);
    }

    // Load the database from the portable PHP dump.
    // The files may be gzipped.
    foreach ($files as $file) {
      if (substr($file, -3) == '.gz') {
        $file = "compress.zlib://$file";
        require $file;
      }
      $class = 'Drupal\migrate\Tests\Dump\\' . basename($file, '.php');
      $class::load($database);
    }
    return $database;
  }
}
