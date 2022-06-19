<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TRestAPI.php");



    $user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
    $user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";

    $year = $_POST['year'];
    $month = $_POST['month'];
    $pet_id = $_POST['pet_id'];
    $idx = $_POST['idx'];
    $cellNumber = $_POST['cellNumber'];

    $list_start = $_POST['list_start'];






    $api = new TRestAPI("https://walkapi.banjjakpet.com:8080");
//    $api = new TRestAPI("http://stg-walkapi.banjjakpet.com:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
    //$api = new TRestAPI("http://192.168.20.128:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");

    $photo_log = $api->get("/walklog/photo/".$user_id."/".$pet_id."/".$year."/".$month);




    $data = array();
    $list_data = $photo_log["body"];



    $return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
    $r_mode = ($_POST["mode"] && $_POST["mode"] != "") ? $_POST["mode"] : "";





?>
<?php
    if($r_mode){

        if($r_mode == "getTPhoto"){



            foreach($photo_log as $arr){

                foreach($arr as $key => $val ){

                   if( $val["idx"] == $idx){

                        $data = $val["photo_path"];

                   }
                }
            }





            $return_data = array("code" => "000000", "data" => $data);

        }else if($r_mode == "getTList"){

            $list_count = $_POST['list_count'];

            $list_start_count = $list_count * $list_start;



            $sliced_data = array_slice($list_data,$list_start_count,$list_start);




            $return_data = array("code" => "000000", "data" => $sliced_data);



        }





    }








echo json_encode($return_data, JSON_UNESCAPED_UNICODE);

?>













