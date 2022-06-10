<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TRestAPI.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");



    $user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
    $user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";
//
//    $year_month = $_POST['year'];
//    $year = substr($year_month, 0, 4);
//    $month = substr($year_month, 4, 6);
//    $pet_id = $_POST['pet_id'];
//    $target_path = $_POST['track_map_path'];

    //$api = new TRestAPI("https://walkapi.banjjakpet.com:8080");
    $api = new TRestAPI("http://stg-walkapi.banjjakpet.com:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
    //$api = new TRestAPI("http://192.168.20.128:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");

//    $months_log = $api->get("/walklog/photo/".$user_id."/".$pet_id."/".$year."/".$month);

//    $year_log = $api->get("/walklog/year/".$user_id."/".$pet_id."/".$year);
//    foreach ($year_log['body'] as $val){
//        if($val["ymonth"] == $year_month){
//            $sum_dist = $val["sum_distance"];
//            $sum_time = $val["sum_time"];
//            $sum_poo = $val["sum_poo"];
//            $sum_pee = $val["sum_pee"];
//            break;
//        }
//    }



    $data = array();
    $return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
    $r_mode = ($_POST["mode"] && $_POST["mode"] !== "") ? $_POST["mode"] : "";



?>


<?php
    if($r_mode){


//
//        if($r_mode == "get_walking_month_list"){
//
//            $page = (isset($_POST["page"])) ? $_POST["page"] : 0 ;
//            $pageLimit = (isset($_POST["pageLimit"])) ? $_POST["pageLimit"] : 10;
//
//
//
//
//
//        }




        if($r_mode == "getTPhoto"){
            $cnt = 0 ;

            $data[$cnt] = 1234;

            $return_data = array("code" => "000000", "data" => $data);
        }


        echo json_encode($return_data, JSON_UNESCAPED_UNICODE);

        return $return_data;
    }










?>













