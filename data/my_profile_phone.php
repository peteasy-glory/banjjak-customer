<? 
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/MCASH_SEED.php");

function cipher($seedStr, $seedKey) {
    if( $seedStr == "" ) return "";
    return decodeString($seedStr, getKey($seedKey));
}

function getKey( $value ){
    $padding = "123456789123456789";
    $tmpKey = $value;
    $keyLength = strlen( $value );
    if( $keyLength < 16 ) $tmpKey = $tmpKey.substr($padding, 0, 16-$keyLength);
    else  $tmpKey = substr( $tmpKey, strlen( $tmpKey )-16,  strlen( $tmpKey ) );
    for($i = 0 ; $i < 16; $i++) {
        $result = $result.chr(ord( substr( $tmpKey, $i, 1 ))^($i+1));
    }
    return $result;
}

$Svcid      = $_POST["Svcid"     ];
$Name       = $_POST["Name"];
$No         = $_POST["No"        ];
$Commid           = $_POST["Commid"    ];
$Resultcd   = $_POST["Resultcd"  ];

$Name                   = cipher($Name, "1901230619012306");
$No                             = cipher($No, "1901230619012306");
$Commid         = cipher($Commid, "1901230619012306");

$Name = iconv("euc-kr", "utf-8", $Name);

//$request_method = $_SERVER["REQUEST_METHOD"];

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

//if ($request_method == "POST"){
  $new_phone = $_REQUEST["phone"];
  $crypto = new Crypto();

<<<<<<< HEAD
    //이전 번호 가져오기
//  $sql = "select cellphone from tb_customer where id = '".$user_id."';";
//  $old_phone = mysqli_query($connection, $sql);

  //global $connection;
=======
  //이전 번호 가져오기
  $sql = "select cellphone from tb_customer where id = '".$user_id."';";
  $old_phone = mysqli_query($connection, $sql);

//global $connection;
>>>>>>> 703065cf3935c269ff9fb507ae44b3994e716ad9
  $sql = "update tb_customer set cellphone = '".$No."' where id = '".$user_id."';";
  $result = mysqli_query($connection, $sql);

  if($result){
      //발신자 번호 수정
<<<<<<< HEAD
 //     $sql = "update tb_sent_cell_id set cellphone = '".$No."' where cellphone = '".$old_phone."';";
 //     $result = mysqli_query($connection, $sql);
 //     if($result){
          echo "<script>alert('전화번호가 변경되었습니다.'); location.replace('../mypage_main');</script>";
 //     }else{
          //echo "<script>alert('전화번호 변경에 실패했습니다..'); location.replace('../mypage_phone_modify');</script>";
 //     }
=======
      $sql = "update tb_sent_cell_id set cellphone = '".$No."' where cellphone = '".$old_phone."';";
      $result = mysqli_query($connection, $sql);
      if($result){
          echo "<script>alert('전화번호가 변경되었습니다.'); location.replace('../mypage_main');</script>";
      }else{
          //echo "<script>alert('전화번호 변경에 실패했습니다..'); location.replace('../mypage_phone_modify');</script>";
      }
>>>>>>> 703065cf3935c269ff9fb507ae44b3994e716ad9
  }else{
      echo "<script>alert('전화번호 변경에 실패했습니다..'); location.replace('../mypage_phone_modify');</script>";
  }
  //echo json_encode($result);
//}

?>
