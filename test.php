<?php
include_once "lib/DB.php";


class a {
  function __construct() {
    $db = new DaBa();
    $db->_query ("SELECT * FROM wordlist");
  }
}

$a = new a();

?>