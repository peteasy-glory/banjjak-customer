<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$pet_type = ($_POST["pet_type"] && $_POST["pet_type"] != "")? $_POST["pet_type"] : "";


setcookie('pet_type', $pet_type, ['expires'=>time()+(86400*7), 'path'=>'/', 'domain'=>'.'.$_SERVER['HTTP_HOST'], 'samesite' => 'strict']);

echo $_COOKIE['pet_type'];
?>