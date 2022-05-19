<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");

$user_id = (isset($_SESSION['gobeauty_user_id']))? $_SESSION['gobeauty_user_id']:"";
$ir_seq = (isset($_POST['ir_seq']))? $_POST['ir_seq'] : "";
$product_no = (isset($_POST['product_no']))? $_POST['product_no'] : "";
$sort = (isset($_POST['sort']))? $_POST['sort'] : "";
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
                    <button type="button" class="btn btn-confirm" onclick="location.replace('../shop_view?product_no=<?=$product_no?>');">확인</button>
                </div>
            </div>

        </div>
    </div>
</article>
<?php

$sql = "
    UPDATE tb_item_review SET
    is_blind = '1',
    blind_msg = '".$txt."',
    blind_dt = NOW()
    WHERE ir_seq = '".$ir_seq."'
";
$result = mysqli_query($connection, $sql);
if($result){
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

?>

<script>

</script>



