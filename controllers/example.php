<?php

namespace App\Controllers;

use App\System\Controller;
use App\Libraries\MailLib;

class Example extends Controller {

  public function __construct() {
    $this->helper('url');
    $this->library('mail');
  }

  public function says(string $name = "Jean Michel", int $id = null, int $idk = 0) {
    $mail = new MailLib();
    $mail->send('','','','');
    $mo = $this->model('ExampleModel');
    $page_name = "Test";
    $name .= " ($idk) ";
    $this->view("test", compact('page_name', 'name'));
  }

  public function save(string $name = "Jean Michel", int $id = null, int $idk = 0) {
    $mo = $this->model('ExampleModel');
    $page_name = "Saving !!";
    $name .= " ($idk) ## DATA SAVED!";
    $this->view("test", compact('page_name', 'name'));
  }

}
