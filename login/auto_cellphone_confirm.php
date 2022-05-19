<?
/*==========================================================
* ���ϸ� : ci_web.php
* �ۼ��� : ���������
* �ۼ��� : 2014.01
* ��  �� : ��������� Weblink ��� ���� ���� ������
* ��	�� : 0003

* �������� �ҽ� ���Ǻ��濡 ���� å���� ������𽺿��� å���� ���� �ʽ��ϴ�.
* ��û �Ķ���� �� ���� ��  ��������  Okurl�� Return �Ǵ� �Ķ���Ϳ� ������ ����ó�� �����
* ���� �Ŵ����� �ݵ�� �����ϼ���.
* �Ǽ��� ��ȯ�� �� ������� ������������� �����ٶ��ϴ�.
*==========================================================*/
?>

<?
	/*****************************************************************************************
	- �������� ĳ�� ����
	*****************************************************************************************/
	$CASH_GB 		= "CI"; 	//��ǥ��������
	
	/*****************************************************************************************
	- �ʼ� �Է� �׸� 
	*****************************************************************************************/
	$PAY_MODE		= "00"; 	//������ �׽�Ʈ,�ǰ������� ( 00 : �׽�Ʈ����, 10 : �ǰŷ�����   )
	$Siteurl		= "gobeauty.kr"; 		//������������
	$Tradeid		= strval(uniqid()); 		//�������ŷ���ȣ
	
	$CI_SVCID		= "190123066519"; 		//���񽺾��̵�
	$CI_Mode		= "61"; 	// 61:SMS�߼�, 67:SMS�̹߼�
	
	
	//$Okurl 			= "https://".$_SERVER['HTTP_HOST']."/user/mainpage/regist_shop_auth.php";		//����URL : �����Ϸ��뺸������ full Url (��:http://www.mcash.co.kr/okurl.jsp )	
	$Okurl = $_GET['okurl'];

	$Cryptyn		= "Y";			//��ȣȭ ��� ���� (default : Y)
	$Keygb			= "0";			//��ȣȭ Key ���� (0 : CI_SVCID 8�ڸ�, 1��2 : ������ ������ ��� �� ���)
	
	/*****************************************************************************************
	- ���� �Է� �׸� 
	*****************************************************************************************/	
	$Callback		= "";			//SMS,LMS�߽Ź�ȣ
	$Smstext		= "";			//SMS����
	$Lmstitle		= "";			//LMS����
	$Lmstext		= "";			//LMS����
	
	$SUB_CPID		= ""; 		//SUB����� �ĺ��ڵ�
	$DI_CODE		= "X18042419260"; 		//DI������Ʈ�ڵ� (12byte)
	$CI_FIXCOMMID	= "";		//�̵���Ż� ���� �� ���
	$Closeurl		= "";			//��ҹ�ư Ŭ�� �� �̵��� ������ (��:http://www.mcash.co.kr/failurl.jsp)
	
	$MSTR				= ""; 		//�������ݹ麯��
												//���������� �߰������� �Ķ���Ͱ� �ʿ��� ��� ����ϸ� &, % �� ���Ұ� ( �� : MSTR="a=1|b=2|c=3" )
	 
	/*****************************************************************************************
	 - ������ ���� �����׸� ( ����  ����� �� �ֽ��ϴ�  )
	*****************************************************************************************/
	$LOGO_YN		= "N"; 		//������ �ΰ� ��뿩��  ( ������ �ΰ� ���� 'Y'�� ����, ������ ������𽺿� ������ �ΰ� �̹����� �־����  )
	$CALL_TYPE	= "SELF"; 		//����â ȣ����( SELF ��������ȯ , P �˾� )

	
?>


<!--  �������� ������û ������ -->
<html>
<head>
<?
	/*****************************************************************************************
	������������ �Ʒ� js ������ �ݵ�� include
	�� ����ȯ�� ������ ������� ����ڿ� ���� ���
	*****************************************************************************************/
?>
</head>
<body  >

<form name="payForm" accept-charset='euc-kr'>
 
 <table border="1" width="100%" style="display:none;">
 	<tr>
 		<td align="center" colspan="6"><font size="15pt"><b>�޴�������Ȯ�� SAMPLE PAGE</b></td> 
 	</tr>
 	
 	<tr> 		
 		<td align="center">��ǥ��������</td>
 		<td align="center">CASH_GB</td>
 		<td><input type="text" name="CASH_GB" id="CASH_GB" value="<?echo $CASH_GB ?>"></td>
		<td align="center">���񽺾��̵�</td>
 		<td align="center">CI_SVCID</td>
 		<td><input type="text" name="CI_SVCID" id="CI_SVCID"  value="<?echo $CI_SVCID ?>"></td>
 	</tr>
 	
 	<tr> 		
 		<td align="center">��ȣȭ��뿩��</td>
 		<td align="center">Cryptyn</td>
 		<td><input type="text" name="Cryptyn" id="Cryptyn" value="<?echo $Cryptyn ?>"></td>
		<td align="center">��ȣȭKey����</td>
 		<td align="center">Keygb</td>
 		<td><input type="text" name="Keygb" id="Keygb"  value="<?echo $Keygb ?>"></td>
 	</tr>

	<tr>
 		<td align="center">����â ȣ����</td>
 		<td align="center">CALL_TYPE</td>
 		<td><input type="text" name="CALL_TYPE" id="CALL_TYPE" value="<?echo $CALL_TYPE ?>"></td>
		<td align="center">������ �ΰ� ��뿩��</td>
 		<td align="center">LOGO_YN</td>
 		<td><input type="text" name="LOGO_YN" id="LOGO_YN" value="<?echo $LOGO_YN ?>"></td>
 	</tr>
 	
 	<tr>
 		<td align="center">�����ܰ豸��</td>
 		<td align="center">CI_Mode</td>
 		<td><input type="text" name="CI_Mode" id="CI_Mode" value="<?echo $CI_Mode ?>"></td>
 		<td align="center">DI������Ʈ�ڵ�</td>
 		<td align="center">DI_CODE</td>
 		<td><input type="text" name="DI_CODE" id="DI_CODE" value="<?echo $DI_CODE ?>"></td>
 	</tr> 
 	<tr>
 		<td align="center">������������</td>
 		<td align="center">Siteurl</td>
 		<td><input type="text" name="Siteurl" id="Siteurl" value="<?echo $Siteurl ?>"></td>
 		<td align="center">SUB�����ĺ�</td>
 		<td align="center">SUB_CPID</td>
 		<td><input type="text" name="SUB_CPID" id="SUB_CPID" value="<?echo $SUB_CPID ?>"></td>
 	</tr>
 	<tr>
 		<td align="center">�߽Ź�ȣ</td>
 		<td align="center">Callback</td>
 		<td><input type="text" name="Callback" id="Callback" size="30" value="<?echo $Callback ?>"></td>
 		<td align="center">SMS����</td>
 		<td align="center">Smstext</td>
 		<td><input type="text" name="Smstext" id="Smstext" size="30" value="<?echo $Smstext ?>"></td>
 	</tr>
 	<tr>
 		<td align="center">LMS����</td>
 		<td align="center">Lmstitle</td>
 		<td><input type="text" name="Lmstitle" id="Lmstitle" size="40" value="<?echo $Lmstitle ?>"></td>
 		<td align="center">LMS����</td>
 		<td align="center">Lmstext</td>
 		<td><textarea name="Lmstext" id="Lmstext" rows="6" value="<?echo $Lmstext ?>"></textarea></td>
 	</tr>
 	<tr>
 		<td align="center">�������ŷ���ȣ</td>
 		<td align="center">Tradeid</td>
 		<td><input type="text" name="Tradeid" id="Tradeid" size="30" value="<?echo $Tradeid ?>"></td>
 		<td align="center">�ŷ�����</td>
 		<td align="center">PAY_MODE</td>
 		<td>
 			<select name="PAY_MODE">
 				<option value="10">�Ǽ���(10)</option>
 				<option value="00">�׽�Ʈ(00)</option>
 			</select>
 		</td> 
 	</tr>
 	<tr>
 		<td align="center">����URL</td>
 		<td align="center">*Okurl</td>
 		<td><input type="text" name="Okurl" id="Okurl" size="30" value="<?echo $Okurl ?>"></td>
 		<td align="center">Okurl��ȣȭ</td>
 		<td align="center">Cryptokurl</td>
 		<td>
 			<select name="Cryptokurl" id="Cryptokurl">
 				<option value="Y">���</option>
 			</select>
		</td>
 	</tr>	
 	<tr>
 		<td align="center">��������</td>
 		<td align="center">CI_FIXCOMMID</td>
 		<td>
 			<select name="CI_FIXCOMMID" id="CI_FIXCOMMID">
 				<option value="">������</option>
 				<option value="SKT">SKT</option>
 				<option value="KTF">KTF</option>
 				<option value="LGT">LGT</option>
 				<option value="SKR">SKT�˶���</option>
 			</select>
		</td>
		<td align="center">Closeurl</td>
 		<td align="center">Closeurl</td>
 		<td><input type="text" name="Closeurl" id="Closeurl" size="30" value="<?echo $Closeurl ?>"></td>
 	</tr>
 	
 	<tr>
 		<td align="center" colspan="6"> </td>
 	</tr>
 	<tr>
 		<td align="center" colspan="6"><input type="button" value="������" onclick="payRequest()"></td>
 	</tr> 	
 </table>
 
</form>
</body>
</html>

<script src="https://auth.mobilians.co.kr/js/ext/ext_inc_comm.js"></script>
<script language="javascript">
        MCASH_PAYMENT(document.payForm);                
</script>

