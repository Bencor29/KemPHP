<?php

namespace App\Controllers;

use App\System\Controller;

class Example extends Controller {

  public function __construct() {
    $this->helper('url');
  }

  public function says(string $name = "Jean Michel", int $id = null, int $idk = 0) {
    $mo = $this->model('ExampleModel');
    $page_name = "Test";
    $name .= " ($idk) ";
    $this->view("test", compact('page_name', 'name'));
    dd($mo->getUsers());
  }

}
