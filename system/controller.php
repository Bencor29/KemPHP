<?php

namespace App\System;

use App\System\BladeOne;
use App\System\ConfigLoader;
use App\System\Session;
use App\System\loadFile;
use App\System\LibrariesLoader;
use \Exception;

class Controller {

  private static $_DIR;
  private static $_MODEL_DIR;
  private static $_HELPER_DIR;
  private static $_LIB_DIR;
  private static $_VIEW_PATH;
  private static $_COMPILED_PATH;

  public static function setWD(string $dir) {
    Controller::$_DIR = $dir;

    $cnf = new ConfigLoader();
    $dirs = $cnf->load('directories');

    Controller::$_MODEL_DIR = $dirs['models'] . '/';
    Controller::$_HELPER_DIR = $dirs['helpers'] . '/';
    Controller::$_LIB_DIR = $dirs['libraries'] . '/';

    Controller::$_VIEW_PATH = $dir . '/' . $dirs['views'];
    Controller::$_COMPILED_PATH = $dir . '/' . $dirs['compiled'];
  }

  /**
  * Load a view
  * $viewName string The view name
  * $args array The arguments for the view
  */
  public function view(string $viewName, array $args = []) {
    $blade = new BladeOne(Controller::$_VIEW_PATH, Controller::$_COMPILED_PATH, BladeOne::MODE_DEBUG);
    echo $blade->run($viewName, $args);
  }

  /**
  * Load a model
  * $modelName string The model name
  */
  public function model(string $modelName) {
    $name = getFileName($modelName);

    if(!loadFile(Controller::$_MODEL_DIR, $name, 'model')) {
      return;
    }

    $class = "App\\Models\\$modelName";
    if(!class_exists($class)) {
      error("Unknown model \"$modelName\" (Class not found)");
      die();
    }

    try {
      $model = new $class();
    } catch(Exception $e) {
      error("Unknown model \"$modelName\" (Class found but failed to instanciate)");
      die();
    }

    return $model;
  }

  /**
  * Load a helper
  * $helperName string The helper name
  */
  public function helper(string $helperName) {
    $name = getFileName($helperName);
    loadFile(Controller::$_HELPER_DIR, $name, 'helper');
  }

  /**
  * Load a library
  * $libName string The library name
  */
  public function library(string $libName) {
    $name = getFileName($libName);
    LibrariesLoader::load($name);
    //loadFile(Controller::$_LIB_DIR, $name, 'library');
  }

}

 ?>
