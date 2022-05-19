<?php

include($_SERVER['DOCUMENT_ROOT']."/common/TFileBridge.php");

$filename = $_REQUEST['filename'];
$down = new TFileDownBridge($filename);
if($down->fileDownload())
    echo "success";
else
    echo "fail";
