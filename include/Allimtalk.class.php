<?php
class Allimtalk{
    public $resultCode;
    public $resultMessge;
    public $cellphone;

    private function sendMessage($templateNo = null, $message = null, $btnName = null, $btnLink = null){
        if(($templateNo != null && $templateNo != "") && ($message != null && $message != "") ){
            $btnCheck = ((($btnName != null && $btnName != "") && ($btnLink != null && $btnLink != ""))?true:false);
			$btnInfo = '{"button":[{"name":"'.$btnName.'","type":"WL","url_pc":"","url_mobile":"'.$btnLink.'"}]}';

			$insert_query = "INSERT INTO ita_talk_tran (
						date_client_req
						,content
						,msg_status
						,recipient_num
						,msg_type
						,sender_key
						,template_code
						,kko_btn_type
						".(($btnCheck)?",kko_btn_info":"")."
					)VALUES(
						sysdate() 
						,'{$message}'
						,'1'
						,'{$this->cellphone}'
						,'1008'
						,'b5b8f3facdf79e6655d479e487d13b95bd8a52b2'
						,'1000004530_{$templateNo}'
						,'2'
						".(($btnCheck)?",'{$btnInfo}'":"")."
					)";


            $result = sql_query($insert_query);
            $this->query = $insert_query;
            if($result){
                $this->resultMessge = "발송 요청 성공";
                return true;
            }else{
                $this->resultMessge = "데이터베이스 오류";
                return false;
            }
        }else{
            $this->resultMessge = "템플릿 번호와 메세지 내용이 필요합니다.";
            return false;
        }
    }


    /*----- 반짝으로 리뉴얼 후 새로운 변경된 템플릿 -----*/
    public function sendOrderShipping_new($customerNumber, $orderNum, $productName, $shoppingInvoice, $btnLink){
        $templateNo = "20013";
        $btnName = "주문배송 확인하기";
        $message = "반려생활의 단짝, 반짝에서 ".$customerNumber."님께서 주문하신 상품의 배송이 시작되어 알려드립니다."
		."\n\n▶ 주문번호 : ".$orderNum
		."\n▶ 상품명 : ".addslashes($productName)
		."\n▶ 택배사(송장번호) : ".$shoppingInvoice
		."\n\n상세한 주문확인과 배송정보는 반짝에서 확인가능합니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

    public function sendOrderDeposit_new($customerNumber, $shippingName, $totalPrice, $orderNum, $payDt, $productName, $btnLink){
        $templateNo = "20011";
        $btnName = "주문내역 확인하기";
        $message = "반려생활의 단짝, 반짝에서 ".$customerNumber."님의 입금확인 완료와 주문내역을 알려드립니다. "
		."\n\n▶ 입금자명 : ".addslashes($shippingName)
		."\n▶ 입금액 : ".$totalPrice
		."\n================="
		."\n\n▶ 주문번호 : ".$orderNum
		."\n▶ 주문일시 : ".$payDt
		."\n▶ 주문상품 : ".addslashes($productName)
		."\n\n\n상세한 주문내역은 반짝에서 확인하실 수 있습니다."
		."\n\n주문하신 상품을 빨리 받아보실수 있게 최선을 다하겠습니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

	public function sendOrderAccount_new($customerNumber, $orderNum, $payDt, $productName, $shippingDt, $totalPrice, $shippingName, $btnLink){
        $templateNo = "20012";
        $btnName = "주문내역 확인하기";
        $message = "반려생활의 단짝, 반짝에서 ".$customerNumber."님의 주문내역(계좌이체)을 알려드립니다."
		."\n\n▶ 주문번호 : ".$orderNum
		."\n▶ 주문일시 : ".$payDt
		."\n▶ 주문상품 : ".addslashes($productName)
		."\n\n계좌이체 주문은 아래 계좌로 입금기한까지 입금을 하셔야 주문이 확정완료되며 입금기한이 지나면 주문이 자동취소되오니 유의하시기 바랍니다."
		."\n\n▶ 입금은행 : 기업은행"
		."\n▶ 입금계좌 : 054-143076-01-013 / 주식회사 펫이지"
		."\n▶ 입금기한 : ".$shippingDt
		."\n=================="
		."\n▶ 입금하실 금액 : ".$totalPrice
		."\n▶ 입금자명 : ".addslashes($shippingName)
		."\n\n\n상세한 주문내역은 반짝에서 확인하실 수 있습니다. ";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

    public function sendOrderReceipt_new($customerNumber, $orderNum, $payDt, $productName, $btnLink){
        $templateNo = "20010";
        $btnName = "주문내역 확인하기";
        $message = "반려생활의 단짝, 반짝에서 ".$customerNumber."님의 주문내역을 알려드립니다."
		."\n\n▶ 주문번호 : ".$orderNum
		."\n▶ 주문일시 : ".$payDt
		."\n▶ 주문상품 : ".addslashes($productName)
		."\n\n상세한 주문내역은 반짝에서 확인하실 수 있습니다."
		."\n\n주문하신 상품을 빨리 받아보실수 있게 최선을 다하겠습니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }



}
