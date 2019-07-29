<?php

  namespace App\System;

  use App\System\ConfigLoader;
  use App\System\loadFile;

  class LibrariesLoader {

    private static $_PATH_LIB = null;
    private static $_PATH_HELP = null;
    private static $_REQUIRED = ['libraries' => [], 'helpers' => []];
    private static $_LOADED = ['libraries' => [], 'helpers' => []];

    public static function load($libName) {
      $name = getFileName($libName);
      $cnf = new ConfigLoader();
      if(LibrariesLoader::$_PATH_LIB == null) {
        LibrariesLoader::$_PATH_LIB = $cnf->load('directories')['libraries'] . '/';
      }
      if(LibrariesLoader::$_PATH_HELP == null) {
        LibrariesLoader::$_PATH_HELP = $cnf->load('directories')['helpers'] . '/';
      }

      if(LibrariesLoader::needLoad($libName, 'libraries')) {
        LibrariesLoader::checkRequirements($libName);

        foreach(LibrariesLoader::$_REQUIRED['helpers'] as $help) {
          loadFile(LibrariesLoader::$_PATH_HELP, $help, 'helper');
          array_push(LibrariesLoader::$_LOADED['helpers'], $help);
        }

        for($i = count(LibrariesLoader::$_REQUIRED['libraries']) - 1; $i >= 0; $i--) {
          loadFile(LibrariesLoader::$_PATH_LIB, LibrariesLoader::$_REQUIRED['libraries'][$i], 'library');
          array_push(LibrariesLoader::$_LOADED['libraries'], LibrariesLoader::$_REQUIRED['libraries'][$i]);
        }
      }
    }

    private static function checkRequirements($libName) {
      array_push(LibrariesLoader::$_REQUIRED['libraries'], $libName);
      $name = getFileName($libName);
      $cnf = new ConfigLoader();

      if(file_exists(ConfigLoader::$baseDir . LibrariesLoader::$_PATH_LIB . $name . '.json')) {
        $required = $cnf->load($name, LibrariesLoader::$_PATH_LIB);
        if(isset($required['libraries'])) {
          foreach($required['libraries'] as $lib) {
            if(LibrariesLoader::needLoad($lib, 'libraries')) {
              array_push(LibrariesLoader::$_REQUIRED['libraries'], $lib);
              LibrariesLoader::checkRequirements($lib);
            }
          }
        }
        if(isset($required['helpers'])) {
          foreach($required['helpers'] as $help) {
            if(LibrariesLoader::needLoad($help, 'helpers')) {
              array_push(LibrariesLoader::$_REQUIRED['helpers'], $help);
            }
          }
        }
      }
    }

    private static function needLoad(string $lib, string $type) {
      return
        !in_array($lib, LibrariesLoader::$_REQUIRED[$type]) &&
        !in_array($lib, LibrariesLoader::$_LOADED[$type]);
    }

  }
