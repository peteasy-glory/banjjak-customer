<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$shop_sql = "select * from tb_shop where customer_id = '" . $user_id . "'";
$shop_result = mysqli_query($connection,$shop_sql);
if ($shop_datas = mysqli_fetch_object($shop_result)) {
    $front_image = $shop_datas->front_image;
    $name = $shop_datas->name;
    $working_years = $shop_datas->working_years;
    $self_introduction = $shop_datas->self_introduction;
    $professional_field = $shop_datas->professional_field;
    $career = $shop_datas->career;
    $license_indexs = $shop_datas->license_indexs;
    $region_and_cost = $shop_datas->region_and_cost;
    $enable_flag = $shop_datas->enable_flag;
    $update_time = $shop_datas->update_time;
}
    ?>



        <script language="javascript">


            //안드에서 업로드가 끝나면 무조건 호출..
            function uploadEnd(fileName) {

                var newfilename = GetPhotoFilename('artist_front_image', '<?= $user_id ?>', fileName);
                var post_data = 'filepath=' + fileName + '&newfilepath=' + newfilename;
                var formData = new FormData();

                formData.append("filepath", fileName);
                formData.append("newfilepath", newfilename);

                $.ajax({
                    url: 'android/upload_shop_front_image_byapp.php',
                    data: post_data,
                    type: 'POST',
                    success: function(data) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        //alert(error + "네트워크에러");
                        if(xhr.status != 0){
                            alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
                        }
                    }
                });
            }

        </script>


<?php include "../include/bottom.php"; ?>