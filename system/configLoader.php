<?php

namespace App\System;

class ConfigLoader {

  public static $baseDir;
  public static $configDir;

  public static function setWD(string $dir) {
    ConfigLoader::$baseDir = $dir . '/';
    ConfigLoader::$configDir = 'configs/';
  }

  public function load(string $file) {
    $filePath = ConfigLoader::$baseDir . ConfigLoader::$configDir . $file . '.json';

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
