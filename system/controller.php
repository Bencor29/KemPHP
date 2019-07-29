<?php

namespace App\System;

use App\System\BladeOne;
use App\System\ConfigLoader;
use App\System\Session;

class Controller {

  private static $_DIR;
  private static $_MODEL_DIR;
  private static $_HELPER_DIR;
  private static $_VIEW_PATH;
  private static $_COMPILED_PATH;

  public static function setWD(string $dir) {
    Controller::$_DIR = $dir;

    $cnf = new ConfigLoader();
    $dirs = $cnf->load('directories');
    Controller::$_MODEL_DIR = $dir . '/' . $dirs['models'] . '/';
    Controller::$_HELPER_DIR = $dir . '/' . $dirs['helpers'] . '/';
    Controller::$_VIEW_PATH = $dir . '/' . $dirs['views'];
    Controller::$_COMPILED_PATH = $dir . '/' . $dirs['compiled'];
  }

  public function view(string $viewName, array $args = []) {
    $blade = new BladeOne(Controller::$_VIEW_PATH, Controller::$_COMPILED_PATH, BladeOne::MODE_DEBUG);
    echo $blade->run($viewName, $args);
  }

  public function model(string $modelName) {
    $name = strtolower(substr($modelName, 0, 1)) . substr($modelName, 1);
    $path = Controller::$_MODEL_DIR . $name . '.php';

    try {
      if(file_exists($path)) {
        require_once($path);
      } else {
        throw new \Exception("File not found");
      }
    } catch(\Exception $e) {
      error("Unknown model \"$modelName\" (File not found: $path)");
      die();
    }

    $class = "App\\Models\\$modelName";
    if(!class_exists($class)) {
      error("Unknown model \"$modelName\" (Class not found)");
      die();
    }

    try {
      $model = new $class();
    } catch(\Exception $e) {
      error("Unknown model \"$modelName\" (Class found but failed to instanciate)");
      die();
    }

    return $model;
  }

  public function helper(string $helperName) {
    $name = strtolower(substr($helperName, 0, 1)) . substr($helperName, 1);
    $path = Controller::$_HELPER_DIR . $name . '.php';

    try {
      if(file_exists($path)) {
        require_once($path);
      } else {
        throw new \Exception("File not found");
      }
    } catch(\Exception $e) {
      error("Unknown helper \"$helperName\" (File not found: $path)");
      die();
    }
  }

}

 ?>
