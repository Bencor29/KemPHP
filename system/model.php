<?php

namespace App\System;

use ParagonIE\EasyDB\Factory;

class Model {

  protected $db;

  public function __construct() {
    $cnf = new ConfigLoader();
    $dbc = $cnf->load('db');
    try {
      $this->db = Factory::fromArray([
        "mysql:host=$dbc[host];dbname=$dbc[name]",
        $dbc['user'],
        $dbc['pass']
      ]);
      if($this->db == null || $this->db->getPdo() == null) {
        throw new \Exception("DB Error");
      }
    } catch(\Exception $e) {
      error("Failed to establish connection with database");
      die();
    }
  }
}
