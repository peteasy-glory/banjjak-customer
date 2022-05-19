<?php include "../include/top.php"; ?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<div class="top_menu">
    <div class="top_back"><a href="javascript:history.back()"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">개인정보 처리방침</div>
</div>

<?php include "privacy_policy_txt.php"; ?>

<?php include "../include/bottom.php"; ?>