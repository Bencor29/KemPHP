<?php

  use App\System\ConfigLoader;

  function h_url_load_config(string $path, string $sub = null) {
    $cnf = new ConfigLoader();
    $url = $cnf->load('url');
    if($url['base'] === null) {
      $url['base'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    }
    $url['base'] .= '/';
    if($sub != null) {
      $url['base'] .= $sub . '/';
    }
    return $url['base'] . $path;
  }

  function url(string $path) {
    return h_url_load_config($path);
  }

  function image(string $path) {
    return h_url_load_config($path, 'images');
  }

  function css(string $path) {
    return h_url_load_config($path, 'css');
  }

  function js(string $path) {
    return h_url_load_config($path, 'js');
  }
