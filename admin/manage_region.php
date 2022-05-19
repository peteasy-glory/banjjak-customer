<?php
include "../include/top.php";
include "../include/Region.class.php";

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

$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "' and (admin_flag = true or operator_flag = true)";
$result = mysql_query($login_insert_sql);

if ($result_datas = mysql_fetch_object($result)) {
    ?>

    <script>
        function change_region(in_status, in_top, in_middle) {
            $.ajax({
                url: 'change_region_status.php',
                data: {
                    type: in_status,
                    top_region: in_top,
                    middle_region: in_middle
                },
                type: 'POST',
                success: function(data) {
                    location.reload();
                },
                error: function(xhr, status, error) {}
            });
        }
    </script>

    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>영업지역 오픈관리</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <table width="100%" border="1" style="font-size:12px;border:1px solid #999999;border-collapse:collapse;">
        <tr style="font-weight:bold;text-align:center;">
            <td>대분류</td>
            <td>중분류</td>
            <td>관리</td>
        </tr>
        <?php
            $artist_count = 0;
            $original_count = 0;

            $sql = "select middle, id, top, open_flag from tb_region group by top, middle order by id;";
            $result = mysql_query($sql);
            for ($i = 0; $rows = mysql_fetch_object($result); $i = $i + 1) {
                $id = $rows->id;
                $top = $rows->top;
                $middle = $rows->middle;
                $bottom = $rows->bottom;
                $flag = $rows->open_flag;
                ?>
            <tr>
                <td><?= $top ?></td>
                <td><?= $middle ?></td>
                <td align="center">
                    <input onclick="javascript:change_region('<?= $flag ?>', '<?= $top ?>', '<?= $middle ?>');" type="checkbox" <?php
                                                                                                                                        if ($flag) {
                                                                                                                                            echo "checked";
                                                                                                                                        }
                                                                                                                                        ?> />
                </td>
            </tr>
        <?php
            }
            ?>
    </table>
    <br>
    아티스트 : <?= $artist_count ?> 명<br>
    일반고객 : <?= $original_count ?> 명<br>

<?php
    include "../include/buttom.php";
}
?>