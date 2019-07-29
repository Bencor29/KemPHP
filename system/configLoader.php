<?php

namespace App\System;

class ConfigLoader {

  public static $baseDir;
  public static $configDir;

  public static function setWD(string $dir) {
    ConfigLoader::$baseDir = $dir . '/';
    ConfigLoader::$configDir = 'configs/';
  }

  public function load(string $file, string $configDir = null) {
    if($configDir == null) {
      $configDir = ConfigLoader::$configDir;
    }
    $filePath = ConfigLoader::$baseDir . $configDir . $file . '.json';

    if(!file_exists($filePath)) {
      return false;
    }

    try {
      return json_decode(file_get_contents($filePath), true);
    } catch(Exception $e) {
      return false;
    }
  }

}
