<?php include "../include/top.php"; ?>

<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
?>

<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>

<style>
    @font-face {
        font-family: 'SCDream2';
        src: url("../fonts/SCDream2.otf");
    }

    html,
    div,
    p,
    button {
        font-weight: bold;
        font-family: 'SCDream2';
    }

    a {
        text-decoration: none;
    }

    a:link {
        color: white;
    }

    a:visited {
        color: white;
    }

    a:hover {
        color: white;
    }

    a:active {
        color: white;
    }

    .my_reservation {
        position: relative;
        z-index: 5;
        width: 100%;
        text-align: left;
        padding: 10px;
        border: 1 solid #999999;
        margin: auto;
    }

    .change_reservation {
        background-color: #f5bf2e;
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
        font-family: 'SCDream2';
        border: 1px solid #f5bf2e;
        display: inline-block;
        color: #ffffff;
        font-size: 18px;
        font-weight: bold;
        font-style: normal;
        height: 40px;
        line-height: 36px;
        width: 100%;
        text-decoration: none;
        text-align: center;
    }

    .cancel_reservation {
        background-color: #f5bf2e;
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
        border: 1px solid #f5bf2e;
        display: inline-block;
        color: #777777;

        font-size: 15px;
        font-weight: bold;
        font-style: normal;
        height: 36px;
        line-height: 36px;
        width: 88px;
        text-decoration: none;
        text-align: center;
    }

    .event_title {
        color: #000;
        font-size: 20px;
        font-weight: bold;
    }

    .event_body {
        color: #000;
        font-size: 16px;
    }

    .go_button {

        background-color: #f5bf2e;
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
        border: 0px solid #f5bf2e;
        display: inline-block;
        color: #ffffff;
        font-family: Arial;
        font-size: 20px;
        font-weight: bold;
        font-style: normal;
        height: 47px;
        line-height: 47px;
        width: 100%;
        text-decoration: none;
        text-align: center;
    }

    input[type=text],
    select {
        width: 100%;
        padding: 10px 20px 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 14px;
        font-family: 'SCDream2';
        font-weight: bold;
    }

	input[type=text],
	input[type=number],
	input[type=submit] { 
		-webkit-appearance: none;
	}

    .top_menu {
        height: 51px;
        position: relative;
    }

    .top_title {
        width: 100%;
        height: 19px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        padding: 15px 0px 15px 0px;
        display: table;
        border-bottom: 0.5px solid #e1e1e1;
    }

    .top_title p {
        margin: 0px;
    }

    .top_back {
        position: absolute;
        bottom: 11px;
        left: 10px;
    }
</style>
<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>/regist_shop_auth.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>반짝 입점 신청</p>
    </div>
</div>

<form action="<?= $mainpage_directory ?>/insert_artist_regist.php" method="POST">
    <input type="hidden" name="step" value="2" />
    <table style="width:90%; margin: 0px auto; margin-top: 20px; font-size:20px;">
        <tr>
            <td height="10px"></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:17px;">
                <b>영업 가능 지역 선택</b>
            </td>
        </tr>
        <tr>
            <td height="5px"></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:14px;">
                (대표적인 지역만 선택하시면 됩니다. 추후 상세 설정 과정이 있습니다.)
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:14px;">
                <select name="top_region" id="top_region">
                    <?php
                    //$top_sql = "select distinct top from tb_region where open_flag = true;";
                    $top_sql = "select distinct top from tb_region;";
                    $top_result = mysql_query($top_sql);
                    while ($top_datas = mysql_fetch_object($top_result)) {
                        $top = $top_datas->top;
                        echo "<option value='$top'>$top</option>";
                    }
                    ?>
                </select>
                <select name="middle_region" id="middle_region">
                </select>

            </td>
        </tr>
        <tr>
            <td height="10px"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" href="#" class="change_reservation" value="다 &nbsp; &nbsp;&nbsp;&nbsp; 음">
            </td>
        </tr>
    </table>
</form>

<script>
    var top_region = document.getElementById('top_region');
    var middle_region = document.getElementById('middle_region');

    function add_middle() {
        var selected_top = top_region.options[top_region.selectedIndex].value;
        var post_data = 'top_region=' + encodeURI(selected_top);
        $.ajax({
            url: '<?= $shop_directory ?>/get_middle_region.php',
            data: post_data,
            type: 'POST',
            success: function(data) {
                var array_middle = data.split(",");
                var select = document.getElementById('middle_region');
                select.options.length = 0; // clear out existing items
                for (var i = 0; i < array_middle.length; i++) {
                    var d = array_middle[i];
                    select.options.add(new Option(d, d))
                }
            },
            error: function(xhr, status, error) {}
        });
    }

    add_middle();

    top_region.addEventListener('change', function(e) {
        add_middle();
    });
</script>
<br>
<br><br><br>
<br><br><br>
<?php include "../include/bottom.php"; ?>