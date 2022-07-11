<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$squence = $_REQUEST['seq'];
if (!$squence) {
    $squence = 1;
}
//
$_ymd_start = trim($_REQUEST['ymd_start'])?trim($_REQUEST['ymd_start']):date("Y-m-d");
$_ymd_end = trim($_REQUEST['ymd_end'])?trim($_REQUEST['ymd_end']):date("Y-m-d");


// 권한체크
$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "' and (admin_flag = true or operator_flag = true)";
$result = mysql_query($login_insert_sql);

if ($result_datas = mysql_fetch_object($result)) {

    // 통계 결과
    $_r_stat = "";
    //
    $_sql = "select * from tb_cron_log where (date(reg_dt) >= '{$_ymd_start}' and date(reg_dt) <= '{$_ymd_end}')";
    //$_sql = "select * from tb_cron_log where result like '%\"failure\":1,%'"; 
    //
    $result = mysql_query($_sql);
    while($_row = mysql_fetch_assoc($result)){
        
        $_tmp_str_data = $_row['data'];     // 발송내용 to firebase
        $_tmp_str_rslt = $_row['result'];   // 발송결과 from firebase
        $_tmp_str_dt_reg = $_row['reg_dt']; // 발송일시

        $_tmp_json_data = json_decode($_tmp_str_data);
        $_tmp_json_rslt = json_decode($_tmp_str_rslt);
        
        // 통계 집계
        // - 전체
        if(isset($_tmp_json_data->{'to'})) {
            $_r_stat['tot']['ids']['to'] += 1;   // 수신1
        } else {
            $_r_stat['tot']['ids']['registration_ids'] += 1;   // 멀티캐스트 메시지(둘 이상의 등록 토큰으로 전송된 메시지)의 수신자를 지정
        }
        $_r_stat['tot']['success'] += $_tmp_json_rslt->{'success'};
        $_r_stat['tot']['failure'] += $_tmp_json_rslt->{'failure'};
        if( $_tmp_json_rslt->{'failure'} == 1) {    // 실패 이력
            $_tmp_error = $_tmp_json_rslt->{'results'}[0]->{'error'};   // str from firebase
            $_r_stat['tot']['failure_error'][ $_tmp_error ] += 1;   
        }

    }// while
    ?>

    <style>
        input[type=text],
        input[type=password] {
            width: 100%;
            height: 25px;
            border: 1px solid #cbcaca;
            font-size: 13px;
        }

        .go_login {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd));
            background: -moz-linear-gradient(center top, #c123de 5%, #a20dbd 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd');
            background-color: #c123de;
            -webkit-border-top-left-radius: 6px;
            -moz-border-radius-topleft: 6px;
            border-top-left-radius: 6px;
            -webkit-border-top-right-radius: 6px;
            -moz-border-radius-topright: 6px;
            border-top-right-radius: 6px;
            -webkit-border-bottom-right-radius: 6px;
            -moz-border-radius-bottomright: 6px;
            border-bottom-right-radius: 6px;
            -webkit-border-bottom-left-radius: 6px;
            -moz-border-radius-bottomleft: 6px;
            border-bottom-left-radius: 6px;
            text-indent: 0;
            border: 1px solid #a511c0;
            display: inline-block;
            color: #ffffff;
            font-family: Arial;
            font-size: 15px;
            font-weight: bold;
            font-style: normal;
            //	height:30px;
            line-height: 30px;
            width: 100%;
            text-decoration: none;
            text-align: center;
            padding: 5px 20px;
            margin: 8px 0;
        }

        .go_login:hover {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de));
            background: -moz-linear-gradient(center top, #a20dbd 5%, #c123de 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
            background-color: #a20dbd;
        }

        .go_login:active {
            position: relative;
            top: 1px;
        }

        .cancelbtn {
            width: auto;
            padding: 10px 18px;
            background-color: #f44336;
        }

        .imgcontainer {
            text-align: center;
            margin: 24px 0 12px 0;
        }

        img.avatar {
            width: 40%;
            border-radius: 50%;
        }

        .container {
            padding: 10px;
        }

        span.psw {
            float: right;
            padding-top: 3px;
        }

        /* Change styles for span and cancel button on extra small screens */
        @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }

            .cancelbtn {
                width: 100%;
            }
        }

        .find_password {
            font-size: 14px;
            text-decoration: none;
        }

        .find_password:hover {
            color: #000000;
        }

        .find_password:active {
            color: #000000;
        }

        .find_password:link {
            color: #000000;
        }

        .find_password:visited {
            color: #000000;
        }
    </style>

    <div class="header-back-btn"><a href="/pet/admin/manage_push_to_all.php"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>푸시 메시지 - 발송 통계</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">


<div style='padding:25px;'>

    <form action="manage_push_to_all_stat.php" method="get">

        <table border='0' cellspacing='0' cellpadding='5'>
        <tbody>
        <tr>
            <td><input type='text' name='ymd_start' value='<?=($_ymd_start)?$_ymd_start:date("Y-m-d")?>'></td>
            <td>~</td>
            <td><input type='text' name='ymd_end' value='<?=($_ymd_end)?$_ymd_end:date("Y-m-d")?>'></td>
            <td><input type='submit' name='btn_submit' value='검색'></td>
        </tr>
        </tbody>
        </table>

    </form>

    <br><br>
    <div class=''>발송 통계 (단위 : 건) - <?=number_format($_r_stat['tot']['success'] + $_r_stat['tot']['failure'])?> 건</div>
    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">
    <table width='500' border='1' cellspacing='0' cellpadding='5'>
    <tbody>
    <tr>
        <td>success</td>
        <td style='text-align:right;'><?=number_format($_r_stat['tot']['success'])?></td>
        <td>failure</td>
        <td style='text-align:right;'><?=number_format($_r_stat['tot']['failure'])?></td>
    </tr>
    <tr>
        <td>수신자 : 단일</td>
        <td style='text-align:right;'><?=number_format($_r_stat['tot']['ids']['to'])?></td>
        <td>수신자 : 멀티캐스트 (2인 이상)</td>
        <td style='text-align:right;'><?=number_format($_r_stat['tot']['ids']['registration_ids'])?></td>
    </tr>
    </tbody>
    </table>

    <br><br>
    <div class=''>발송 실패 (단위 : 건) - <?=number_format($_r_stat['tot']['failure'])?> 건의 Error 유형별 집계</div>
    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">
    <table width='500' border='1' cellspacing='0' cellpadding='5'>
    <thead>
    <tr>
        <td>유형</td>
        <td>건 수</td>
    </tr>
    </thead>
    <tbody>
    <?
        if(isset($_r_stat['tot']['failure_error'])) {
            foreach($_r_stat['tot']['failure_error'] as $_i => $_v) {
    ?>
    <tr>
        <td><?=$_i?></td>
        <td style='text-align:right;'><?=number_format($_v)?></td>
    </tr>
    <?
            }
        }
    ?>
    </tbody>
    </table>

</div>

    <?php include "../include/bottom.php"; ?>
<?php } // 권한체크 ?>