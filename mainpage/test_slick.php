
  <link rel="stylesheet" type="text/css" href="../css/slick.css"/>
  <link rel="stylesheet" type="text/css" href="../css/slick-theme.css"/>
  </head>
  <style type="text/css">
.slider {width: 100%;/*margin-top:30px;*//*margin: 100px auto;*/}
.slick-slide {margin: 0px 10px;/*width: 2.25%;height:auto;*/width:350px;/*height:250px;*/}
.slick-slide img {width: 100%;}
.slick-prev:before,
.slick-next:before {color: black;}
.slick-slide {transition: all ease-in-out .3s;/*opacity: .2;*/}
.slick-active {opacity: .5;}
.slick-current {opacity: 1;}
  </style>

<body>

 

  <section class="variable slider">
	<div>
     <a href="https://www.gopet.kr/pet/mainpage/item_product_page.php?no=PE-D-A01"><img src="../images/ban4_2_6_4_2.jpg"></a>
    </div>
	<!--div>
     <a href="https://www.gopet.kr/pet/mainpage/item_product_page.php?no=PE-D-A01"><img src="../images/ban4_2_6_1.jpg"></a>
    </div--> <?php //-- 배너 교체 -- ?>
	<!--div>
     <a href="https://www.gopet.kr/pet/mainpage/item_product_page.php?no=PE-C-A02"><img src="../images/ban5_1_2.jpg"></a>
    </div--> <?php //-- 종료 -- ?>
	<!--div>
     <a href="https://www.gopet.kr/pet/mainpage/item_product_page.php?no=towel01"><img src="../images/ban4_2_5.jpg"></a>
    </div--> <?php //-- 종료 -- ?>
    <!--div>
     <a href="view_event_holiday.php"><img src="../images/ban1_9_1_5.jpg"></a>
    </div--><?php //-- 임시 내림 -- ?>
    <div>
     <a href="main_review_event.php"><img src="../images/ban2_3_1.jpg"></a>
    </div>
    <div>
     <a href="view_event2.php"><img src="../images/ban3_4.jpg"></a>
    </div>
  </section>

  

  
  <script type="text/javascript" src="../js/slick.min.js"></script>

 <script type="text/javascript">
	$(function(){
		var tslick = $(".variable").slick({
			dots: true,
			infinite: true,
			variableWidth: true
		});
	});

</script>
