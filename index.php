<?php

  error_reporting(E_ALL);
  ini_set('display_errors',1);
  ini_set('error_reporting', E_ALL);
  ini_set('display_startup_errors',1);
  error_reporting(-1);

  $required = [
    'system/router',
    'system/session',
    'system/configLoader',
    'system/controller',
    'system/utilities',
    'system/blade',
    'system/model',

    'system/paragonie/corner/CornerTrait',
    'system/paragonie/corner/CornerInterface',
    'system/paragonie/corner/Error',
    'system/paragonie/corner/Exception',

    'system/paragonie/easydb/EasyDB',
    'system/paragonie/easydb/EasyStatement',
    'system/paragonie/easydb/Factory',
    'system/paragonie/easydb/Exception/ExceptionInterface',
    'system/paragonie/easydb/Exception/ConstructorFailed',
    'system/paragonie/easydb/Exception/InvalidIdentifier',
    'system/paragonie/easydb/Exception/InvalidTableName',
    'system/paragonie/easydb/Exception/MustBeArrayOrEasyStatement',
    'system/paragonie/easydb/Exception/MustBeOneDimensionalArray',
    'system/paragonie/easydb/Exception/QueryError'
  ];

  foreach($required as $file) {
    require_once(__DIR__ . '/' . $file . '.php');
  }

  use App\System\ConfigLoader;
  ConfigLoader::setWD(__DIR__);
  App\System\Controller::setWD(__DIR__);

  $cnf = new ConfigLoader();

  $libraries = $cnf->load('libraries');
  $dir = __DIR__ . '/' . $cnf->load('directories')['libraries'] . '/';
  foreach($libraries as $file) {
    $path = $dir . $file . '.php';
    try {
      if(file_exists($path)) {
        require_once($path);
      } else {
        throw new Exception("File not found.");
      }
    } catch(Exception $e) {
      error("Failed to load library \"$file\" (path: $path)");
    }
  }

  App\System\Session::start();
  App\System\Router::route(__DIR__);
