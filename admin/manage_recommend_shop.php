<?php
include "../include/configure.php";
include "../include/session.php";
include "../include/db_connection.php";
include "../include/php_function.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
$str_search = $_REQUEST['str_search'];
?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" /> 

<?php
$url = $_SERVER['REQUEST_URI'];

//특정 페이지에서 숫자를 전화번호로 인식하여 보이지 않는 문제 때문에 safari 내의 전화번호 검출 기능 off
if (strpos($url, "manage_my_reservation") !== false) {
    ?>
    <meta name="format-detection" content="telephone=no">
<?php
}
?>

<style>
    @font-face {
        font-family: 'SCDream2';
        src: url("../fonts/SCDream2.otf");
    }

    html,
    div,
    font,
    a {
        font-family: 'SCDream2';
    }

    input {
        -webkit-appearance: none;
        border-radius: 0;
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
        top: 13px;
        position: absolute;
        bottom: 11px;
        left: 10px;
    }
</style>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<div class="top_menu">
    <div class="top_back"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>펫샵 추천 관리</p>
    </div>
</div><br>
<?php
$squence = $_REQUEST['seq'];
if (!$squence) {
    $squence = 1;
}

$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "' and (admin_flag = true or operator_flag = true)";
$result = mysql_query($login_insert_sql);

if ($result_datas = mysql_fetch_object($result)) {
    ?>

    <script>
        function change_recommend(in_status, id) {
            $.ajax({
                url: 'change_recommend_status.php',
                data: {
                    type: in_status,
                    customer_id: id
                },
                type: 'POST',
                success: function(data) {
                    // alert(data);
                    location.reload();
                },
                error: function(xhr, status, error) {}
            });
        }
    </script>
    <div class="container">
        <div class="row mb-3 border_bottom py-2">
            <div class="col-5 text-center font-weight-bold">펫샵명</div>
            <div class="col-5 text-center font-weight-bold">아이디</div>
            <div class="col-2 text-center font-weight-bold">ON/OFF</div>
        </div>
        <div class="row mb-3">
            <?php
                $sql = "select * from tb_shop where open_flag = 1 and enable_flag = 1 order by name;";
                $result = mysql_query($sql);
                $cnt = 1;
                while ($rows = mysql_fetch_object($result)) {
                    $customer_id = $rows->customer_id;
                    $name = $rows->name;
                    $update_time = $rows->update_time;
                    $enable_flag = $rows->enable_flag;
                    $is_recommend = $rows->is_recommend;
                    ?>
                <div class="col-5 text-center border my-1"><?= $name ?></div>
                <div class="col-5 text-center border my-1"><?= $customer_id ?></div>
                <div class="col-2 text-center border my-1">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="check_recommend_<?= $cnt ?>" onclick="javascript:change_recommend('<?= $is_recommend ?>', '<?= $customer_id ?>');" <?php if ($is_recommend) {
                                                                                                                                                                                                                echo "checked";
                                                                                                                                                                                                            } ?>>
                        <label class="custom-control-label" for="check_recommend_<?= $cnt ?>"></label>
                    </div>
                </div>
            <?php
                $cnt++;
                }
                ?>
        </div>
    </div>
    <br>
<?php
}
?>