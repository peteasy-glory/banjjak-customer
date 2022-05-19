<?php
    include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

    $customer_id = (isset($_POST['customer_id']))? $_POST['customer_id'] : "";
    $second_type = (isset($_POST['second_type']))? $_POST['second_type'] : "";
    $direct_title = (isset($_POST['direct_title']))? $_POST['direct_title'] : "";
    $pet_type = (isset($_POST['pet_type']))? $_POST['pet_type'] : "";
    
    if($pet_type == "dog"){
        if($second_type == '직접입력' && $direct_title != ''){
            $product_sql = "SELECT * FROM tb_product_dog_static WHERE customer_id ='{$customer_id}' AND second_type = '직접입력' AND direct_title = '{$direct_title}'";
        }else{
            $product_sql = "SELECT * FROM tb_product_dog_static WHERE customer_id ='{$customer_id}' AND second_type = '{$second_type}'";
        }
    }else{
        $product_sql = "SELECT * FROM tb_product_cat WHERE customer_id ='{$customer_id}'";
    }

    $product_result = mysqli_query($connection,$product_sql);

    if($product_rows = mysqli_fetch_object($product_result)){
        echo json_encode($product_rows);
    }
?>