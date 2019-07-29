<?php

  use App\System\ConfigLoader;

  function h_url_load_config() {
    $cnf = new ConfigLoader();
    $url = $cnf->load('url');
    if($url['base'] === null) {
      $url['base'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    }
  }

  function url(string $path) {
    $url = h_url_load_config();
    return $url['base'] . $path;
  }

  function image() {
    $url = h_url_load_config();
    return $url['base'] . $url['images'] . $path;
  }

  function css() {
    $url = h_url_load_config();
    return $url['base'] . $url['css'] . $path;
  }

  function js() {
    $url = h_url_load_config();
    return $url['base'] . $url['js'] . $path;
  }
