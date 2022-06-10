<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TRestAPI.php");



    $user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
    $user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";

    $year = $_POST['year'];
    $month = $_POST['month'];
    $pet_id = $_POST['pet_id'];
    $idx = $_POST['idx'];


    //$api = new TRestAPI("https://walkapi.banjjakpet.com:8080");
    $api = new TRestAPI("http://stg-walkapi.banjjakpet.com:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
    //$api = new TRestAPI("http://192.168.20.128:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");

    $photo_log = $api->get("/walklog/photo/".$user_id."/".$pet_id."/".$year."/".$month);




    $data = array();
    $return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
    $r_mode = ($_POST["mode"] && $_POST["mode"] != "") ? $_POST["mode"] : "";





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



            foreach($photo_log as $arr){

                foreach($arr as $key => $val ){

                   if( $val["idx"] == $idx){

                        $data = $val["photo_path"];

                   }
                }
            }





            $return_data = array("code" => "000000", "data" => $data);

        }





    }








echo json_encode($return_data, JSON_UNESCAPED_UNICODE);

?>













