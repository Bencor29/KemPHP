<?php

namespace App\System;

use App\System\ConfigLoader;

class Router {

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

    $matches = Router::checkRouteArgs($page, $cnf['routes']);
    if($matches) {
      $page = $matches['route'];
    }

    if(isset($cnf['routes'][$page])) {
      $page = $cnf['routes'][$page];
    }

    if($matches) {
      for($i = count($matches['args']) - 1; $i >= 0; $i--) {
        $page = str_replace('$' . $i, $matches['args'][$i], $page);
      }
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

  private static function checkRouteArgs(string $request, array $routes) {
    $route_f = false;
    foreach($routes as $route => $value) {
      $route_m = str_replace('[:num]', '([0-9]+)', $route);
      $route_m = str_replace('[:string]', '([a-zA-Z0-9_]+)', $route_m);

      if(preg_match_all("#^$route_m$#", $request, $matches)) {
        $route_f = [];
        $route_f['route'] = $route;
        $route_f['args'] = [];
        foreach($matches as $match) {
          array_push($route_f['args'], $match[0]);
        }
      }
    }
    return $route_f;
  }

}
