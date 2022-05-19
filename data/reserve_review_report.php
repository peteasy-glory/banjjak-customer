<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");

$user_id = (isset($_SESSION['gobeauty_user_id']))? $_SESSION['gobeauty_user_id']:"";
$review_seq = (isset($_POST['review_seq']))? $_POST['review_seq'] : 0;
$artist_id = (isset($_POST['artist_id']))? $_POST['artist_id'] : "";
$review_id = (isset($_POST['review_id']))? $_POST['review_id'] : "";
$payment_log_seq = (isset($_POST['payment_log_seq']))? $_POST['payment_log_seq'] : 0;
$sort = (isset($_POST['sort']))? $_POST['sort'] : 0;
$detail = (isset($_POST['detail']))? $_POST['detail'] : "";

$txt = $user_id."|".$sort."|".$detail;
?>
<article id="firstRequestMsg_report" class="layer-pop-wrap">
    <div class="layer-pop-parent">
        <div class="layer-pop-children">

            <div class="pop-data alert-pop-data">
                <div class="pop-body">
                    <div class="msg-txt"></div>
                </div>
                <div class="pop-footer">
                    <button type="button" class="btn btn-confirm" onclick="location.replace('../reserve_view?type=beauty&artist_id=<?=$artist_id?>');">확인</button>
                </div>
            </div>

        </div>
    </div>
</article>
<?php

$update_sql = "
    UPDATE tb_usage_reviews SET ".
    //is_report = 1,
    "is_blind = 1,
    update_time = NOW()
    WHERE review_seq = '".$review_seq."'
";
$update_result = mysqli_query($connection, $update_sql);

if($update_result){
    $insert_sql = "
        INSERT INTO `tb_usage_reviews_report` 
            (`review_customer_id`, `report_customer_id`, `review_seq`, `payment_log_seq`, `report`, `report_txt`, `is_delete`, `update_time`) 
        VALUES 
            ('".$review_id."', '".$user_id."', ".$review_seq.", ".$payment_log_seq.", ".$sort.", '".$detail."', 0, NOW());
    ";
    $insert_result = mysqli_query($connection, $insert_sql);

    if($insert_result){
        ?>
        <script>
            //location.replace('shop_view?product_no=<?//=$product_no?>//')
            $('#firstRequestMsg_report').find('.msg-txt').text('신고되었습니다.');
            pop.open('firstRequestMsg_report');
        </script>
        <?php
    }else{
        ?>
        <script>
            $('#firstRequestMsg_report').find('.msg-txt').text('데이터 오류입니다.');
            pop.open('firstRequestMsg_report');
        </script>
        <?php
    }

}


?>

<script>

</script>



