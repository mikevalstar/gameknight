<?php
require "../../lib/lessphp/lessc.inc.php";

$less = new lessc;

header("Content-type: text/css");
try {
  //echo $less->compileFile("../../less/manage.less");
  $less->checkedCompile("../../less/manage.less", "css.css");
  echo file_get_contents('css.css');
} catch (exception $e) {
  echo "fatal error: " . $e->getMessage();
}