<?php

function dd($str) {
	echo '<pre>';
	print_r($str);
	echo '</pre>';
}

function error($str) {
  echo <<<EOF
  <p style="padding: 10px; background-color: #ff4848; color: white;">
    <b>Error: </b> $str
  </p>
EOF;
}
