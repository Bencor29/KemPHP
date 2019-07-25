<?php

namespace App\System;

use App\System\BladeOne;
use App\System\ConfigLoader;
use App\System\Session;

class Controller {

  private static $_DIR;
  private static $_MODEL_DIR;

  public static function setWD(string $dir) {
    Controller::$_DIR = $dir;

    $cnf = new ConfigLoader();
    Controller::$_MODEL_DIR = $dir . '/' . $cnf->load('directories')['models'] . '/';
  }

  private $viewPath;
  private $compiledPath;

  public function __construct() {
    $cnf = (new ConfigLoader())->load("directories");
    $this->viewPath = ConfigLoader::$baseDir . $cnf['views'];
    $this->compiledPath = ConfigLoader::$baseDir. $cnf['compiled'];

    Session::start();
    // TODO Vérif loged
  }

  public function view(string $viewName, array $args = []) {
    $blade = new BladeOne($this->viewPath, $this->compiledPath, BladeOne::MODE_DEBUG);
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

}

 ?>
