<?php
include($_SERVER['DOCUMENT_ROOT']."/common/TRestAPI.php");

$pet_id = $_POST["id"];

$idx = str_replace("pet_", "", $pet_id);

$api = new TRestAPI("http://192.168.20.128:8080"
    , "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
$mypet_log = $api->get("/walklog/pet/log/".$idx);

echo json_encode($mypets, JSON_UNESCAPED_UNICODE);


?>