<?php
    include($_SERVER['DOCUMENT_ROOT']."/include/global.php");


    // 변수 정리
    $customer_id = $_POST['customer_id'];
    $type = str_replace( "0,", "", $_POST['type']);
    $type = str_replace( ",0", "", $type);
    $comp = $_POST['comp'];
    $brand = $_POST['brand'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $contents = $_POST['contents'];



    if($customer_id != ""){
        $sql = "
                        INSERT INTO tb_item_shop_entry (
                            `customer_id`, `name`, `brand`, `cellphone`, `email`,
                            `entry_type`, `comment`
                        ) VALUES (
                            '".$customer_id."', '".addslashes($comp)."', '".addslashes($brand)."', '".str_replace('-', '', $phone)."', '".$email."',
                            '".$type."', '".addslashes($contents)."'
                        )
                    ";
        $result = mysqli_query($connection, $sql);

        if($result){
            $return_data = array("code" => "000000", "data" => "상품입점/제휴문의 등록되었습니다.");
        }else{
            $return_data = array("code" => "020702", "data" => "상품입점/제휴문의 등록에 실패했습니다.");
        }
    }else{
        $return_data = array("code" => "020701", "data" => "중요 데이터가 누락되었습니다.");
    }

    echo json_encode($return_data, JSON_UNESCAPED_UNICODE);


