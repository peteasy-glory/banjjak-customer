<?php include "../include/top.php"; ?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>


iframe {width:100%;height: calc(100% - 50px);border:0px;overflow: hidden;}
caption {height:1px; font-size:0; line-height:0;}

/* 기본 게시판 view */
table.bbs_default {width:100%; border-collapse: collapse; margin:53px auto 0;font-family: 'NanumGothic';}
table.bbs_default.view tr:first-child th,table.bbs_default.view tr:first-child td{border-top:3px solid #005baa;}
table.bbs_default.view th{width:20%;padding:15px 10px;border-bottom:1px solid #d9d9d9;background:#f8f8f8;font-weight:600;text-align:center;}
table.bbs_default.view td{padding:15px 10px 15px 25px;border-bottom:1px solid #d9d9d9;}
table.bbs_default.view thead tr:first-child th{border-top:3px solid #005baa;border-bottom:1px solid #aaa}
table.bbs_default.view .subject td{font-weight:700}
table.bbs_default.view .subject.delete .subject_text{font-weight:400;color:#888}
table.bbs_default.view .delete .delete_info{font-size:0.9em;color:#2e84d1}
table.bbs_default.view .bbs_content {min-height:150px;text-align:left}
table.bbs_default.view .bbs_content img{max-width:640px;height:auto;}
table.bbs_default.view .bbs_content .photo_area{margin-bottom:25px;text-align:center;}
table.bbs_default.view .bbs_content .photo_view{position:relative;width:auto;margin:15px auto;font-size:0;line-height: 0;}
table.bbs_default.view .bbs_content .photo_view .photo_wrap{display:inline-block;position:relative;}
table.bbs_default.view .bbs_content .photo_view img{max-width:640px;max-height:480px;padding:5px;border:1px solid #eee}
table.bbs_default.view .bbs_content .photo_zoom{display:block;overflow:hidden;position:absolute;bottom:7px;right:7px;z-index:10;width:25px;height:25px;padding:6px;background:url("/common/images/board/mask.png");font-size:12px;}
table.bbs_default.view .bbs_content .photo_zoom a{display:block;width:25px;height:25px;background: url('/common/images/board/bbs_icon.png') no-repeat 0 -200px;font-size:0;line-height:0;text-indent: 100%;}
table.bbs_default.view  ul.view_attach{}
table.bbs_default.view  ul.view_attach li{margin:1px 0}
table.bbs_default.view  ul.view_attach li img{margin-right:5px;}
table.bbs_default.view  ul.view_attach li .file_size{margin-left:10px; font-size:11px;letter-spacing:-1px;font-family : 'dotum'; color:#2e84d1}
table.bbs_default.view  ul.view_attach li .download_num{margin-left:10px; font-size:11px;letter-spacing:-1px; font-family : 'dotum'; color:#2e84d1}
table.bbs_default.view  .bbs_attach_preview {display:inline-block;height:20px;line-height:20px;margin-left:5px;padding-left:8px;border:1px solid #686868;background:#686868;color:#fff;font-size:0.9em;vertical-align: middle}
table.bbs_default.view  .bbs_attach_preview .ico_preview{display:inline-block;width:20px;height:20px;margin:0 0 1px 10px;background:#fff url("/common/images/board/bbs_icon.png") no-repeat -95px -46px;vertical-align: top}

@media only screen and (max-width:640px){
	table.bbs_default.view colgroup {display:none;}
    table.bbs_default.view tr{display:block;padding:4px 0;}
    table.bbs_default.view tr:after{display:block; clear:both; content:"."; visibility:hidden; height:0;}
    table.bbs_default.view tr{display: inline-block;}
    table.bbs_default.view tr{display: block;}
    table.bbs_default.view th{display:block;float:left;clear:left;width:100%;padding:10px 3% 0;border:none;background:none;color:#222;text-align: left;box-sizing:border-box }
    table.bbs_default.view td{display:block;float:left;clear:left;width:100%;padding:0 3% 10px;box-sizing:border-box }
    table.bbs_default.view tr:first-child th{border-bottom:none}
    table.bbs_default.view tr:first-child td{border-top: none}
    table.bbs_default.view .bbs_content img{width:100%;max-width:none;height:auto;}
    table.bbs_default.view .bbs_content .photo_view{width:100%}
    table.bbs_default.view .bbs_content .photo_view img{max-width:100%;max-height:600px;padding:0;border:none;}
    table.bbs_default.view  ul.view_attach li a:not(.bbs_attach_preview){display:block}
    table.bbs_default.view  ul.view_attach li .file_size{margin-left:22px}
    table.bbs_default.view input[type="radio"], table.bbs_default.view input[type="checkbox"]{width:auto;height:auto;padding-left:0;}
}
@media only screen and (max-width:500px) {
    table.bbs_default.view tr{border-bottom:none}
    table.bbs_default.view th{display:block;padding:10px 3% 0;background:none;text-align: left}
    table.bbs_default.view td{display:block;padding:0 3% 10px}
    table.bbs_default.view [data-cell-header]:before {content: attr(data-cell-header); display: inline-block; }
}

.box {position:relative; min-height:50px; /*margin-top:10px;*/ margin-bottom:30px; padding-top:20px; padding-right:30px; padding-bottom:20px; padding-left:145px; border-top-width:2px; border-top-style:solid; border-top-color:#232c3b; border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#dde1e2; background-color:#f4f4f4;}
.box:before {display:block; position:absolute; top:20px; left:40px; width:75px; height:52px; background-image:url('/common/images/template/box_icon.png'); background-repeat:no-repeat; background-position:center center; content:'';}
.box.type4 {font-family: 'NanumGothic';padding-left:30px; border-top-width:0; border-bottom-width:0; background-color:#f6f6f6;}
.box.type4:before {display:none;}

@media screen and (max-width:800px) {
	.bbs_slide{max-width:600px;}
	table.bbs_default.view .bbs_content img{max-width:100%; max-height:100%;}
}
.bbs_default{margin-top:57px;}
</style>


<div class="top_menu">
	<div class="top_back"><a href="<?=$mainpage_directory?>/"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
	<div class="top_title">
		<p class="header_font">사업자정보확인</p>
	</div>
</div>
<?php
//$url = "https://www.ftc.go.kr/bizCommPop.do?wrkr_no=1578601070";
// $user_agent = $_SERVER['HTTP_USER_AGENT'];
// if ($user_agent) {
// 	$token_index = strpos($user_agent, "APP_GOBEAUTY_AND");
// 	if ($token_index > 0) {
// 		$url = "https://www.ftc.go.kr/bizCommPop.do?wrkr_no=1578601070";
// 	}else{
// 		$url = "http://www.ftc.go.kr/bizCommPop.do?wrkr_no=1578601070";
// 	}
// }

$response_data = get('https://www.ftc.go.kr/bizCommPop.do', array('wrkr_no'=>'1578601070'));
// 아래처럼 .wrap_count, .reco 사이에 긁어오면 될것 같습니다.
preg_match('/<body>(.*?)<\/body>/s', $response_data, $matches);
echo $matches[0];

// GET 방식 함수
function get($url, $params=array()) 
{ 
    $url = $url.'?'.http_build_query($params, '', '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

?>
<!--iframe src="<?=$url?>"></iframe-->

<?php include "../include/bottom.php"; ?>

