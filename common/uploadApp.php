<?php
/*    $log_dir = "/opt/apache/htdocs/pet/bootpay";

    $log_file = fopen($log_dir."/log.txt", "a");
    $headers = getallheaders();
    while (list ($header, $value) = each ($headers)) {
        $log = $header.":".$value."\r\n";
        fwrite($log_file, $log);
        if ($header == "uploaded_file") {
                fwrite($log_file, "AAAAAAAAAAAAAAAA");
        }
    }
    fclose($log_file);
*/

    //$file_path = "/opt/apache/htdocs/pet/upload/appupload/";
    $file_path = "/var/www/html/subdomain/banjjak_sol/customer/upload/appupload/";
    $file_path = $file_path . basename( $_FILES['uploaded_file']['name']);
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
        echo "success";
    } else{
        echo "fail";
    }




/*
    $log_dir = "/home/hosting_users/pickmongb/www/bootpay";

    $log_file = fopen($log_dir."/log.txt", "a");
    $headers = getallheaders();
    while (list ($header, $value) = each ($headers)) {
	$log = $header.":".$value."\r\n";
        fwrite($log_file, $log);
        if ($header == "uploaded_file") {
        	fwrite($log_file, "AAAAAAAAAAAAAAAA");
	}
    }
    fclose($log_file);


    $file_path = "/home/hosting_users/pickmongb/www/upload/appupload/";
     
    $file_path = $file_path . basename( $_FILES['uploaded_file']['name']);
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
        echo "success";
    } else{
        echo "fail";
    }
*/
 ?>
