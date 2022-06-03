<?php
include($_SERVER['DOCUMENT_ROOT'] . "/common/TRestAPI.php");

$type = $_POST["type"];
$owner_id = $_POST["owner_id"];
$pet_id = $_POST["pet_id"];
$year = $_POST["year"];

$api = new TRestAPI("https://walkapi.banjjakpet.com:8080");
//$api = new TRestAPI("http://stg-walkapi.banjjakpet.com:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
//$api = new TRestAPI("http://192.168.20.128:8080"
//    , "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
if($type == 1){
    $month_log = $api->get("/walklog/pet/".$owner_id."/".$pet_id."/".$year);
    echo json_encode($month_log, JSON_UNESCAPED_UNICODE);
}else{
    echo "";
}

