<?
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");



?>
<!-- header -->
<header id="header">
    <div class="header-left">
        <a href="<?=$_SESSION['backurl1']?>" class="btn-page-ui btn-page-prev"><div class="icon icon-size-24 icon-page-prev">페이지 뒤로가기</div></a>
    </div>
    <div class="page-title" id="title"></div>
</header>
<!-- //header -->

<!-- container -->
<section id="container">
    <!-- page-body -->
    <div class="page-body fix-bottom-page" id="event">


    </div>
    <!-- //page-body -->
    <div class="common-bottom-ui left">
        <a href="shop_cart" class="btn-page-cart"><em>0</em></a>
    </div>
    <div class="common-bottom-ui right">
        <button type="button" id="btnPageTop" class="btn-page-top" onclick="common.pageMove(0);">상단 바로가기</button>
    </div>
</section>
<!-- //container -->
<script>



    var lastScrollTop = 0;
    var timer = null;
    var customer_id = "<?=$user_id ?>";

    $(document).ready(function(){
        // 장바구니 개수
        $.ajax({
            url: './data/item_ajax.php',
            data: {
                mode: "get_cart_cnt",
                customer_id: "<?=$user_id ?>"
            },
            type: 'POST',
            dataType: 'JSON',
            success: function(data){
                $(".btn-page-cart em").text(data.data);
            }
        });

    })


    // 세자리 숫자 콤마
    Number.prototype.format = function() {
        if (this == 0) return 0;

        var reg = /(^[+-]?\d+)(\d{3})/;
        var n = (this + '');

        while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

        return n;
    };

    // 문자열 타입에서 쓸 수 있도록 format() 함수 추가
    String.prototype.format = function() {
        var num = parseFloat(this);
        if (isNaN(num)) return "0";

        return num.format();
    };

</script>

</body>
</html>
