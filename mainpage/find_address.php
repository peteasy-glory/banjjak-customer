<?php include "../include/top.php";?>

<style type="text/css">
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

    .save_region {
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
        color: #ffffff;
        font-size: 15px;
        font-weight: bold;
        font-family: 'SCDream2';
        font-style: normal;
        height: 38px;
        line-height: 38px;
        width: 90%;
        text-decoration: none;
        text-align: center;
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

<script>
	// 주소의 파라미터 값 변수로 가져오기
	function getParameterByName(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	var chk = getParameterByName('chk');
	console.log("result=" + chk);

    function open_payment_type() {
        window.name = "parentForm";
        location.href = 'find_region.php?backurl=find_address.php?chk=' + chk;
        return true;
    }

    function save_data() {
        var address = '<?= $_SESSION['gobeauty_address'] ?>';
        if (!address) {
            $.MessageBox({
                buttonDone: "확인",
                message: "주소찾기를 눌러서 주소를 입력해주세요."
            }).done(function() {});
            return false;
        }

        var rest_addrest = document.getElementById('request_address_rest').value;
        if (!rest_addrest) {
            $.MessageBox({
                buttonDone: "확인",
                message: "상세 주소를 입력해주세요."
            }).done(function() {});
            return false;
        }

        var post_data = 'key=rest_address&rest_address=' + rest_addrest;
        add_cart(post_data, address + ' ' + rest_addrest);
    }

    function add_cart(post_data, full_address) {
        $.ajax({
            url: '../artist/set_cart_session.php',
            data: post_data,
            type: 'POST',
            success: function(data) {
                location.href = 'request_artist_offline.php?chk=' +chk;
            },
            error: function(xhr, status, error) {}
        });
    }
</script>
<div class="top_menu">
    <div class="top_back"><a href="#" onclick="location.href='request_artist_offline.php?chk=' + chk"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>방문요청 주소</p>
    </div>
</div>



<center>
    <table border="1" style="padding:2px;width:90%; margin: 0px auto; margin-top: 20px; font-size:14px;border:1px solid #999999;border-collapse:collapse;">
        <tr height="40px">
            <td rowspan="2" width="15%" align="center"><img src="<?= $image_directory ?>/region.png" width="34px"></td>
            <td align="center" width="85%" id="address_td">
                <table width="100%">
                    <tr>
                        <td>
                            <?php
                            if ($_SESSION['gobeauty_address']) {
                                ?>
                                <a href="#" onclick="location.href='../mainpage/find_region.php?backurl=find_address.php&chk=' + chk">
                                    <?php
                                        $address = $_SESSION['gobeauty_address'];
                                        echo str_replace("|", "<br>", $address);
                                        ?>
                                </a>
                            <?php
                            } else {
                                ?>
                                <input type="text" id="request_address" style="width:100%;border:1px;" placeholder="주소 찾기" onclick="open_payment_type()" readonly>
                            <?php
                            }
                            ?>
                        </td>
                        <td align="right">
                            <img src="<?= $image_directory ?>/find.png" width="25px" onclick="open_payment_type()">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr height="40px">
            <td colspan="3">
                <input type="text" id="request_address_rest" style="width:95%;border:1px;padding:3px;" placeholder="상세주소 입력" required>
            </td>
        </tr>
    </table>
</center>
<div style="height:6px;width:100%;"></div>
<div style="height:6px;width:100%;"></div>
<center>
    <a href="#" onclick="save_data();" class="save_region">저 장</a>
</center>


<?php include "../include/bottom.php"; ?>