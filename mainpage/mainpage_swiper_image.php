<link rel="stylesheet" href="../css/reset.css?<?= filemtime($upload_static_directory . $css_directory . '/reset.css') ?>">
<link rel="stylesheet" href="../css/viSimpleSlider.css?<?= filemtime($upload_static_directory . $css_directory . '/viSimpleSlider.css') ?>">
<meta name="apple-mobile-web-app-capable" content="yes" />
<script type="text/javascript" src="../js/jquery.easing.js"></script>
<script type="text/javascript" src="../js/vinyli.viSimpleSlider.js"></script>

<div id="testDiv">
    <ul>
        <li data-link="view_event1.php">
            <img src="../images/main_bans_3.png">
        </li>
        <li data-link="main_review_event.php">
            <img src="../images/after_main_bans.png">
        </li>
        <li data-link="view_event2.php">
            <img src="../images/200107_01.jpg">
        </li>
    </ul>
</div>

<script>
    $('#testDiv').viSimpleSlider({
        ease: 'easeOutQuart',
        autoPlay: true,
        autoTime: 6000,
        speed: 400,
        mobileSwipe: true,
        desktopSwipe: true
    });
</script>