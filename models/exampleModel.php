<?php

namespace App\Models;

use App\System\Model;

class ExampleModel extends Model {

  public function getUsers() {
    $rows = $this->db->run('SELECT * FROM test');
    return $rows;
  }

}
