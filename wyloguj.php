<?php


function __autoload($class_name) {
    include $class_name . '.php' ;
}
session_start();

$reg = new Register_new ;
$reg->_logout();
header('Location:index.php');

?>