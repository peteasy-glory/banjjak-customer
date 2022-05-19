<?php include "../include/top.php"; ?>
<?php include "../include/MCASH_SEED.php"; ?>

<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
?>

<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

function cipher($seedStr, $seedKey)
{
    if ($seedStr == "") return "";
    return decodeString($seedStr, getKey($seedKey));
}

function getKey($value)
{
    $padding = "123456789123456789";
    $tmpKey = $value;
    $keyLength = strlen($value);
    if ($keyLength < 16) $tmpKey = $tmpKey . substr($padding, 0, 16 - $keyLength);
    else  $tmpKey = substr($tmpKey, strlen($tmpKey) - 16,  strlen($tmpKey));
    for ($i = 0; $i < 16; $i++) {
        $result = $result . chr(ord(substr($tmpKey, $i, 1)) ^ ($i + 1));
    }
    return $result;
}

$Svcid      = $_POST["Svcid"];
$Name       = $_POST["Name"];
$No         = $_POST["No"];
$Commid           = $_POST["Commid"];
$Resultcd   = $_POST["Resultcd"];

$Name                   = cipher($Name, "1901230619012306");
$No                             = cipher($No, "1901230619012306");
$Commid         = cipher($Commid, "1901230619012306");

$Name = iconv("euc-kr", "utf-8", $Name);
// $Name;
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
 
<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>/view_event2.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>반짝 입점 신청</p>
    </div>
</div>



<form action="<?= $mainpage_directory ?>/insert_artist_regist.php" method="POST" id="regist_shop_auth" style="margin-top:73px;">
    <input type="hidden" name="step" value="1" />
    <input type="hidden" name="cp" value="<?= $No ?>" />
    <table style="width:90%; margin: 0px auto; font-size:20px; font-weight: bold;">
        <tr style="text-align:center;">
            <td class="event_title" width="50%">
                <a href="regist_shop_auth.php">
                    <div style="padding:15px 15px 5px 15px; margin-bottom: 10px;border-bottom:3px solid #f5bf2e;color:#f5bf2e;">개인</div>
                </a>
            </td>
            <td class="event_title" width="50%">
                <a href="regist_shop_auth2.php">
                    <div style="padding:15px 15px 5px 15px; margin-bottom: 10px;border-bottom:3px solid #999999;color:#999999;">사업자</div>
                </a>
            </td>
        </tr>
        <tr>
            <td height="10px"></td>
        </tr>
        <?php
        if ($Resultcd == '0000') {
            ?>
            <tr>
                <td colspan="2" style="font-size:18px;">
                    <b>입점 희망자</b><br>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-size:14px; padding-top: 14px;">
                    이름<br>
                    <input type="text" name="artist_real_name" id="artist_real_name" style="width:100%;font-size:14px;" value="<?= $Name ?>" placeholder="본명입력" required>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-size:14px;">
                    전화번호<br>
                    <input type="text" name="artist_real_cellphone" id="artist_real_cellphone" style="width:100%;font-size:14px;" value="<?= $No ?>" placeholder="전화번호" required>
                </td>
            </tr>
            <tr>
                <td height="10px"></td>
            </tr>
            <tr>
                <td colspan="2" style="font-size:17px;">
                    <input type="submit" href="#" class="change_reservation" value="다 &nbsp; &nbsp;&nbsp;&nbsp; 음">
                </td>
            </tr>
        <?php
        } else {
            ?>
            <tr>
                <td colspan="2" style="font-size:17px;">
                    <a href="auto_cellphone_confirm.php" class="change_reservation" value="휴대폰 본인인증">휴대폰 본인인증</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
</form>

<br>
<br><br><br>
<br><br><br>
<?php include "../include/bottom.php"; ?>