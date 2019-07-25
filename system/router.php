<?php

namespace App\System;

use App\System\ConfigLoader;

class Router {

  // TODO: Loader les configs
  // Returner se qu'il faut
  public static function route(string $dir) {
    $dir .= '/';

    $cnfLoad = new ConfigLoader;
    $cnf = $cnfLoad->load('router');
    $cnfDir = $cnfLoad->load('directories');

    if(!isset($_GET['page']) || $_GET['page'] == null) {
      $page = $cnf['default_route'];
    } else {
      $page = $_GET['page'];
    }

    $cnf['routes'] = Router::routeConf($cnf['routes']);


    if(isset($cnf['routes'][$page])) {
      $page = $cnf['routes'][$page];
    }

    $args = explode('/', $page);

    $ctr = $args[0];
    array_shift($args);

    if(!preg_match("/^[a-zA-Z-0-9_]+\@[a-zA-Z-0-9_]+$/", $ctr)) {
      $pattern = "/[a-zA-Z-0-9_\@]/";
      $invalid = preg_replace($pattern, "", $ctr);

      error("404 not found!");
      die();
    }

    $ctr_split = explode('@', $ctr);
    $size = sizeof($ctr_split);
    if($size != 2) {
      $size--;
      error("404 not found!");
      die();
    }

    $contr = $ctr_split[0];
    $funct = $ctr_split[1];

    $contrName = strtolower(substr($contr, 0, 1)) . substr($contr, 1);
    $controllers = $cnfDir['controllers'];
    $pathControl = $dir . $controllers . "/$contrName.php";

    if(!file_exists($pathControl)) {
      error("404, controller file not found!");
      die();
    }
    require_once($pathControl);
    $class = "App\\Controllers\\$contr";

    if(!class_exists($class)) {
      error("Unknown controller ($class)");
      die();
    }

    $classInst = new $class();
    try {
      call_user_func_array(array($classInst, $funct), $args);
    } catch(Exception $e) {
      error($e);
      die();
    }
  }

  private static function routeConf($conf, $prefix = null) {
    $cnfRoutes = [];
    if($prefix != null) {
      $prefix .= '/';
    }

    foreach($conf as $key => $val) {
      if(!is_array($val)) {
        $cnfRoutes[$prefix . $key] = $val;
      } else {
        $sub = Router::routeConf($val, $key);
        foreach($sub as $k => $v) {
          $cnfRoutes[$prefix . $k] = $v;
        }
      }
    }
    return $cnfRoutes;
  }

}
