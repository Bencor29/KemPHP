<?php

  namespace App\System;

  error_reporting(E_ALL);
  ini_set('display_errors',1);
  ini_set('error_reporting', E_ALL);
  ini_set('display_startup_errors',1);
  error_reporting(-1);

  use App\System\ConfigLoader;
  use App\System\Controller;
  use App\System\Session;
  use App\System\Router;
  use \Exception;

  function requireFile(string $file) {
    require_once(__DIR__ . '/' . $file . '.php');
  }

  function loadFile(string $path, string $file, string $type) {
    $loaded = false;
    $filePath = $path . $file;
    $path = __DIR__ . '/' . $filePath . '.php';
    try {
      if(file_exists($path)) {
        requireFile($filePath);
        $loaded = true;
      } else {
        throw new Exception("File not found.");
      }
    } catch(Exception $e) {
      error("Failed to load $type \"$file\" (path: $path)");
    }
    return $loaded;
  }

  // Loading requirements
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
    requireFile($file);
  }

  // Defining working directory
  ConfigLoader::setWD(__DIR__);
  Controller::setWD(__DIR__);


  $cnf = new ConfigLoader();

  // Loading libraries
  $libraries = $cnf->load('libraries');
  $lib_dir = $cnf->load('directories')['libraries'] . '/';
  foreach($libraries as $file_l) {
    loadFile($lib_dir, $file_l, 'library');
  }

  // Loading helpers
  $helpers = $cnf->load('helpers');
  $help_dir = $cnf->load('directories')['helpers'] . '/';
  foreach($helpers as $file_h) {
    loadFile($help_dir, $file_h, 'helper');
  }

  Session::start();
  Router::route(__DIR__);
