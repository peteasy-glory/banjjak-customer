<?php
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$token_index = 0;
if ($user_agent) {
    $token_index = strpos($user_agent, "APP_GOBEAUTY_AND");
}
?>
<style>
    #fixed-menu {
        width: 100%;
        height: 28px;
        background-color: rgba(255, 255, 255, 0.9);
        position: fixed;
        z-index: 9900;
        <?php
        if ($token_index > 0) {
        ?>top: 0px;
        <?php
        } else {
        ?>top: calc(constant(safe-area-inset-top) + 0px);
        top: calc(env(safe-area-inset-top) + 0px);
        <?php
        }
        ?>
        background-size: cover;
        background-position: 0px -20px;
		    padding: 15px 0px 15px 0px;
			border-bottom: 0.5px solid #e1e1e1;

    }

    #fixed-menu img:first-child {
        /*margin-top: 13px;*/
        margin-left: 12px;
    }

    #main-content {
        width: 100%;
        height: 58px;
    }

    #fixed-menu li {
        display: inline-block;
        margin-right: 30px;
    }

    img {
        max-width: 100%;
    }
</style>

<div id="fixed-menu">
    <a href="<?= $mainpage_directory ?>/"><img src="<?= $image_directory ?>/main_logo.png" height="24" /></a>
    <a href="mainpage_my_menu.php" style="position:absolute;z-index:9999;right:12px;top:15px;"><img src="<?= $image_directory ?>/menu.png" width="30px" /></a>

</div>
<div id="main-content">
    <!--img src="slider.png"-->
</div>