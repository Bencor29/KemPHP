<?php

namespace App\Controllers;

use App\System\Controller;
use App\System\Session;

class Example extends Controller {

  public function says(string $name = "Jean Michel") {
    $mo = $this->model('ExampleModel');
    $page_name = "Test";
    $this->view("test", compact('page_name', 'name'));
    dd($mo->getUsers());
  }

}
