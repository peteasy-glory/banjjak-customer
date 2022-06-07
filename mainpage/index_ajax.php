<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

	$data = array();
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";
	
	if($r_mode){
		if($r_mode == "get_shop_list"){							// 0001 상품리스트 가져오기
			$crypto = new Crypto();

			$r_top = ($_POST["top"] && $_POST["top"] != "")? $_POST["top"] : "";
			$r_middle = ($_POST["middle"] && $_POST["middle"] != "")? $_POST["middle"] : "";
			$r_lat = ($_POST["lat"] && $_POST["lat"] != "")? $_POST["lat"] : "";
			$r_lng = ($_POST["lng"] && $_POST["lng"] != "")? $_POST["lng"] : "";
			$r_shop_list = ($_POST["shop_list"] && $_POST["shop_list"] != "")? $_POST["shop_list"] : "";
			$r_limit_0 = ($_POST["limit_0"] && $_POST["limit_0"] != "")? $_POST["limit_0"] : "0";
			$r_limit_1 = ($_POST["limit_1"] && $_POST["limit_1"] != "")? $_POST["limit_1"] : "10";
			$where_qy = "";

			if($r_shop_list != ""){
				if(strpos($r_shop_list, ",") !== false) {  
					$shop_list_arr = explode(",", $r_shop_list);
					$where_qy .= " AND customer_id NOT IN ( ";
					foreach($shop_list_arr AS $key => $value){
						$where_qy .= "'".$value."',";
					}
					$where_qy = substr($where_qy, 0, -1);
					$where_qy .= " ) ";
				}
			}

			if($r_top != "" && $r_middle != ""){
				$enc_region = $crypto->encode(trim($r_top).":".trim($r_middle), $access_key, $secret_key);
				$where_qy .= " AND TRIM(region) = TRIM('".$enc_region."') ";

				$sql = "
					SELECT * 
					FROM tb_request_artist
					WHERE step = '6'
						".$where_qy."
					ORDER BY RAND()
				";// 영업 하는지 안하는지를 체크할 수 없어 전부 호출

				$result = mysqli_query($connection,$sql);
				while($row = mysqli_fetch_assoc($result)){
					$dec_customer_id = $crypto->decode($row["customer_id"], $access_key, $secret_key);

					$sql = "
						SELECT *
						FROM tb_shop 
						WHERE open_flag = true 
							AND enable_flag = true 
							AND customer_id = '".$dec_customer_id."'
					";
					$sql2 = $sql;

					$result2 = mysqli_query($connection,$sql);
					while($row2 = mysqli_fetch_assoc($result2)){
						$data[$dec_customer_id][] = $row2;
						$data[$dec_customer_id]["dec_customer_id"] = $dec_customer_id;
					}
				}

				$sort = array();
				foreach($data AS $key => $value){


					$sort[$key] = "0";
					foreach($value AS $key2 => $value2){
						
						if(is_array($value2) && $value2['is_recommend']=='1'){
							$sort[$key] = "1";
						}						
					}
					
				}
				
				array_multisort($sort, SORT_DESC, $data);
			}else if($r_lat != "" && $r_lng != ""){

				$sql = "
					SELECT *,
						(6371*acos(cos(radians(".$r_lat."))*cos(radians(sh.lat))*cos(radians(sh.lng)-radians(".$r_lng."))+sin(radians(".$r_lat."))*sin(radians(sh.lat)))) AS distance
					FROM tb_shop as sh
					WHERE lat IS NOT NULL
						AND lng IS NOT NULL
						AND open_flag = true 
						AND enable_flag = true 
					ORDER BY distance 
					LIMIT ".$r_limit_0.", ".$r_limit_1."
				";
				$result = mysqli_query($connection,$sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[$row["customer_id"]][] = $row;
					$data[$row["customer_id"]]["dec_customer_id"] = $row["customer_id"];

					$customer_id = $crypto->encode(trim($row['customer_id']), $access_key, $secret_key);
					$sql = "
						SELECT *
						FROM tb_request_artist
						WHERE customer_id = '".$customer_id."'
					";
					$result2 = mysqli_query($connection,$sql);
					$row2 = mysqli_fetch_assoc($result2);
					$data[$row["customer_id"]][] = $row2;
				}
			}else{
				$sql = "
					SELECT * 
					FROM tb_shop 
					WHERE open_flag = true 
						AND enable_flag = true 
						".$where_qy."
					ORDER BY RAND()
					LIMIT ".$r_limit_0.", ".$r_limit_1."
				";

				$result = mysqli_query($connection,$sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[$row["customer_id"]][] = $row;
					$data[$row["customer_id"]]["dec_customer_id"] = $row["customer_id"];
				}
			}

			$return_data = array("code" => "000000", "data" => $data, "sql" => $sql2, "post" => $_POST);
		} else if ($r_mode == "getDistanceShopList") {
			$lat = (isset($_POST["lat"]))? $_POST["lat"] : "37.517305";
			$lng = (isset($_POST["lng"]))? $_POST["lng"] : "127.047502";
			$limtKm = (isset($_POST["limtKm"]))? $_POST["limtKm"] : "10";
			$page = (isset($_POST["page"]))? $_POST["page"] : 0;
			$pageLimit = (isset($_POST["pageLimit"]))? $_POST["pageLimit"] : 10;
			$tabMenu = (isset($_POST["tabMenu"]))? $_POST["tabMenu"] : "best";
			$addrTop = (isset($_POST["addrTop"]))? $_POST["addrTop"] : "서울";
			$addrMiddle = (isset($_POST["addrMiddle"]))? $_POST["addrMiddle"] : "강남구";

			$paging = $page * $pageLimit;
			$sql = "";
			$defaultDistanceSql = "SELECT * FROM ( SELECT *,
				(
					6371 * acos (
					cos ( radians({$lat}) )
					* cos( radians( lat ) )
					* cos( radians( lng ) - radians({$lng}) )
					+ sin ( radians({$lat}) )
					* sin( radians( lat ) )
					)
				) AS distance
				FROM tb_shop
				WHERE  
					lat IS NOT NULL
					AND lng IS NOT NULL
					AND open_flag = true 
					AND enable_flag = true ) AS distanceCur WHERE distanceCur.distance <= {$limtKm} ORDER BY distanceCur.distance ASC ";
			if ( $tabMenu == "best" ) {
				$crypto = new Crypto();

				$customerIdArray = "";

				//반경 10km 점주
				$result = mysqli_query($connection,$defaultDistanceSql);
				while($row = mysqli_fetch_assoc($result)){
					$customerIdArray .= "'".$row["customer_id"]."',";
				}

				//지역 점주				
				$enc_region = $crypto->encode(trim($addrTop).":".trim($addrMiddle), $access_key, $secret_key);
				$where_qy = " AND TRIM(region) = TRIM('".$enc_region."') ";

				$addrSql = " SELECT customer_id FROM tb_request_artist WHERE step = '6' ".$where_qy." ";
				$result = mysqli_query($connection,$addrSql);
				
				while($row = mysqli_fetch_assoc($result)){
					$customerIdArray .= "'".$crypto->decode($row["customer_id"], $access_key, $secret_key)."',";
				}
				
				if ( $customerIdArray != "" ) {
					$customerIdArray = substr($customerIdArray, 0, -1);

					$listSql = "SELECT * FROM
						tb_shop AS a LEFT JOIN 
							(SELECT artist_id, AVG(rating) AS avg_rating 
								FROM tb_usage_reviews WHERE rating IS NOT NULL AND is_delete = 0 GROUP BY artist_id )AS b
						ON a.customer_id = b.artist_id
						WHERE a.lat IS NOT NULL
						AND a.lng IS NOT NULL
						AND a.open_flag = true 
						AND a.enable_flag = true
						AND a.customer_id IN ({$customerIdArray}) ORDER BY ISNULL(b.avg_rating), b.avg_rating DESC LIMIT {$paging}, {$pageLimit};
					";

					$result = mysqli_query($connection,$listSql);
				}
			} else {
				$sql = "{$defaultDistanceSql} LIMIT {$paging}, {$pageLimit}";
	
				$result = mysqli_query($connection,$sql);
			}
			
			$cnt = 0;
			while($row = mysqli_fetch_assoc($result)){
				$customerId = $row["customer_id"];

				$data[$cnt]["customer_id"] = $row["customer_id"];
				$data[$cnt]["name"] = $row["name"];
				$data[$cnt]["is_mainshop_recommend"] = $row["is_mainshop_recommend"];
				$data[$cnt]['distance'] = number_format($row['distance'],1);	
				$data[$cnt]['front_image'] = img_link_change($row['front_image']);		
				if ( $tabMenu == "best" ) {
					$data[$cnt]['rating'] = isset($row["avg_rating"]) ? $row["avg_rating"] : "-";
				} else {
					$data[$cnt]['rating'] = getRating($customerId, $connection);
				}						
				$data[$cnt]['totalService'] = getTotalService($customerId, $connection);
				$data[$cnt]['commentCnt'] = getCommentCount($customerId, $connection);
				$data[$cnt]['workDate'] = getWorkDate($customerId, $connection);
				$data[$cnt]['addr'] = getShopAddress($customerId,$access_key,$secret_key,$connection);

				$cnt++;
			}

			$return_data = array("code" => "000000", "data" => $data);

		} else if ($r_mode == "getDistanceShopMarker") {
			$lat = (isset($_POST["lat"]))? $_POST["lat"] : "37.517305";
			$lng = (isset($_POST["lng"]))? $_POST["lng"] : "127.047502";
			$limtKm = (isset($_POST["limtKm"]))? $_POST["limtKm"] : "10";
			$tabMenu = (isset($_POST["tabMenu"]))? $_POST["tabMenu"] : "best";
			$addrTop = (isset($_POST["addrTop"]))? $_POST["addrTop"] : "서울";
			$addrMiddle = (isset($_POST["addrMiddle"]))? $_POST["addrMiddle"] : "강남구";

			$defaultDistanceSql = "SELECT * FROM 
            ( SELECT *,
                (
                    6371 * acos (
                    cos ( radians({$lat}) )
                    * cos( radians( lat ) )
                    * cos( radians( lng ) - radians({$lng}) )
                    + sin ( radians({$lat}) )
                    * sin( radians( lat ) )
                    )
                ) AS distance
                FROM tb_shop
                WHERE  
                    lat IS NOT NULL
                    AND lng IS NOT NULL
                    AND open_flag = true 
                    AND enable_flag = true 
            ) AS distanceCur WHERE distanceCur.distance <= {$limtKm} ORDER BY distanceCur.distance ASC";
			if ( $tabMenu == "best" ) {
				$crypto = new Crypto();

				$customerIdArray = "";

				//반경 10km 점주
				$result = mysqli_query($connection,$defaultDistanceSql);
				while($row = mysqli_fetch_assoc($result)){
					$customerIdArray .= "'".$row["customer_id"]."',";
				}

				//지역 점주				
				$enc_region = $crypto->encode(trim($addrTop).":".trim($addrMiddle), $access_key, $secret_key);
				$where_qy = " AND TRIM(region) = TRIM('".$enc_region."') ";

				$addrSql = " SELECT customer_id FROM tb_request_artist WHERE step = '6' ".$where_qy." ";
				$result = mysqli_query($connection,$addrSql);
				
				while($row = mysqli_fetch_assoc($result)){
					$customerIdArray .= "'".$crypto->decode($row["customer_id"], $access_key, $secret_key)."',";
				}
				
				if ( $customerIdArray != "" ) {
					$customerIdArray = substr($customerIdArray, 0, -1);

					$listSql = "SELECT * FROM
						tb_shop AS a LEFT JOIN 
							(SELECT artist_id, AVG(rating) AS avg_rating 
								FROM tb_usage_reviews WHERE rating IS NOT NULL AND is_delete = 0 GROUP BY artist_id )AS b
						ON a.customer_id = b.artist_id
						WHERE a.lat IS NOT NULL
						AND a.lng IS NOT NULL
						AND a.open_flag = true 
						AND a.enable_flag = true
						AND a.customer_id IN ({$customerIdArray}) ORDER BY ISNULL(b.avg_rating), b.avg_rating DESC ;
					";

					$result = mysqli_query($connection,$listSql);
				}
			} else {
				$result = mysqli_query($connection,$defaultDistanceSql);
			}
			
			$cnt = 0;
            //$markerData = array();
			while($row = mysqli_fetch_assoc($result)){
				$markerData[$cnt]['customer_id'] = $row['customer_id'];
				$markerData[$cnt]['name'] = $row['name'];
				$markerData[$cnt]['lat'] = $row['lat'];
				$markerData[$cnt]['lng'] = $row['lng'];
				$cnt++;
			}
			$data = $markerData;

			$return_data = array("code" => "000000", "data" => $data);

		} else if ($r_mode == "getDetailShop") {
			$customerId = (isset($_POST["email"]))? $_POST["email"] : "";
			if ( $customerId != "" ) {
				$sql = "SELECT name, front_image  
						FROM tb_shop 
						WHERE 
						customer_id = '{$customerId}' ";

				$result = mysqli_query($connection,$sql);
				$shopData = mysqli_fetch_assoc($result);

				$data['name'] = $shopData['name'];
				$data['front_image'] = img_link_change($shopData['front_image']);
				$data['rating'] = getRating($customerId, $connection);
				$data['totalService'] = getTotalService($customerId, $connection);
				$data['commentCnt'] = getCommentCount($customerId, $connection);
				$data['workDate'] = getWorkDate($customerId, $connection);
				$data['addr'] = getShopAddress($customerId,$access_key,$secret_key,$connection);

				$return_data = array("code" => "000000", "data" => $data);
			} 
		} else if($r_mode == "get_location_top"){				// 0002 선택된 지역 가져오기
			$sql = "
				SELECT DISTINCT top 
				FROM tb_region 
				WHERE open_flag = true
			";
			$result = mysqli_query($connection,$sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data, "post" => $_POST);

		}else if($r_mode == "get_location_middle"){				// 0002 선택된 지역 가져오기
			$r_top_region = ($_POST["top_region"] && $_POST["top_region"] != "")? $_POST["top_region"] : "";

			if($r_top_region != ""){
				$sql = "
					SELECT DISTINCT middle 
					FROM tb_region 
					WHERE top = '".$r_top_region."' 
						AND open_flag = true
				";
				$result = mysqli_query($connection,$sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data, "post" => $_POST);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.", "post" => $_POST);
			}
		}else if($r_mode == "get_main_contents"){				// 0003 메인 화면 데이터 가져오기
			$crypto = new Crypto();

			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$total_service = ""; // 서비스 종류

			if($r_customer_id != ""){
				// 개 시술 여부 확인
				$sql = "
					SELECT *
					FROM tb_product_dog_static
					WHERE customer_id = '".$r_customer_id."'
				";
				$result = mysqli_query($connection,$sql);
				while($row = mysqli_fetch_assoc($result)){
					if ($row["second_type"] == "소형견미용") {
						//$total_service .= "<div><img src='../images/icon_01.png'></div>";
						$total_service .= "소형견,";
					} else if ($row["second_type"] == "중형견미용") {
						//$total_service .= "<div><img src='../images/icon_02.png'></div>";
						$total_service .= "중형견,";
					} else if ($row["second_type"] == "대형견미용") {
						//$total_service .= "<div><img src='../images/icon_03.png'></div>";
						$total_service .= "대형견,";
					} else if ($row["second_type"] == "특수견미용") {
						//$total_service .= "<div><img src='../images/icon_07.png'></div>";
						$total_service .= "특수견,";
					}

					if($row["out_shop_product"] == "1"){
						$data["out_shop_product"] = "1";
					}
				}
				$data["product_dog"] = mysqli_num_rows($result);

				// 고양이 시술 여부 확인
				$sql = "
					SELECT *
					FROM tb_product_cat
					WHERE customer_id = '".$r_customer_id."'
				";
				$result = mysqli_query($connection,$sql);
				while($row = mysqli_fetch_assoc($result)){
					if($row["out_shop_product"] == "1"){
						$data["out_shop_product"] = "1";
					}
				}
				$data["product_cat"] = mysqli_num_rows($result);
				if($data["product_cat"] > 0){
					//$total_service .= "<div><img src='../images/icon_04.png'></div>";
					$total_service .= "고양이,";
				}

				// 매장 / 출장 확인
				$sql = "
					SELECT second_type 
					FROM tb_product_dog_static 
					WHERE out_shop_product = 1 
						AND customer_id = '".$r_customer_id."'
				UNION ALL
					SELECT second_type 
					FROM tb_product_cat 
					WHERE out_shop_product = 1 
						AND customer_id = '".$r_customer_id."'
				";
				$result = mysqli_query($connection,$sql);
				$data["out_count"] = mysqli_num_rows($result);
				if($data["out_count"] > 0){
					//$total_service .= "<div><img src='../images/icon_05.png'></div>";
					$total_service .= "출장,";
				}

				$sql = "
					SELECT second_type 
					FROM tb_product_dog_static 
					WHERE in_shop_product = 1 
						AND customer_id = '".$r_customer_id."'
				UNION ALL
					SELECT second_type 
					FROM tb_product_cat 
					WHERE in_shop_product = 1 
						AND customer_id = '".$r_customer_id."'
				";
				$result = mysqli_query($connection,$sql);
				$data["in_count"] = mysqli_num_rows($result);
				if($data["in_count"] > 0){
					//$total_service .= "<div><img src='../images/icon_06.png'></div>";
					$total_service .= "매장,";
				}

				// 호텔여부 확인
				$sql = "
					SELECT *
					FROM tb_hotel
					WHERE is_delete = '2'
						AND artist_id = '".$r_customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				$row2 = mysqli_fetch_assoc($result2);
				if($row2["is_enable"] == "1"){ // 1
					$total_service .= "호텔,";
				}
				
				// 유치원여부 확인
				$sql = "
					SELECT *
					FROM tb_playroom
					WHERE is_delete = '2'
						AND artist_id = '".$r_customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				$row2 = mysqli_fetch_assoc($result2);
				if($row2["is_enable"] == "1"){ // 1
					$total_service .= "유치원,";
				}

				// 별점 평균
				$sql = "
					SELECT AVG(rating) AS avg_rating 
					FROM tb_usage_reviews 
					WHERE artist_id = '".$r_customer_id."' 
						AND is_delete = 0
						AND rating IS NOT NULL
				";
				$result = mysqli_query($connection,$sql);
				$row = mysqli_fetch_assoc($result);
				$data["rating"] = substr($row["avg_rating"], 0, 3);

				// 댓글 수
				$sql = "
					SELECT COUNT(review_seq) AS review_cnt 
					FROM tb_usage_reviews 
					WHERE artist_id = '".$r_customer_id."' 
						AND is_delete = 0
				";
				$result = mysqli_query($connection,$sql);
				$data["review"] = mysqli_num_rows($result);

				// 영업 시간 및 정기 휴일
				$working_day = "";
				$sql = "
					SELECT * 
					FROM tb_working_schedule AS tws
						INNER JOIN tb_regular_holiday AS trh ON tws.customer_id = trh.customer_id
					WHERE tws.customer_id = '".$r_customer_id."'
				";
				$result = mysqli_query($connection,$sql);
				while($row = mysqli_fetch_assoc($result)){
					$data["working_start"] = $row["working_start"];
					$data["working_end"] = $row["working_end"];
					$data["rest_public_holiday"] = $row["rest_public_holiday"];

					if($row["is_monday"])	{ $working_day .= " 월요일"; }
					if($row["is_tuesday"])	{ $working_day .= " 화요일"; }
					if($row["is_wednesday"]){ $working_day .= " 수요일"; }
					if($row["is_thursday"])	{ $working_day .= " 목요일"; }
					if($row["is_friday"])	{ $working_day .= " 금요일"; }
					if($row["is_saturday"])	{ $working_day .= " 토요일"; }
					if($row["is_sunday"])	{ $working_day .= " 일요일"; }

					if(!$row["is_monday"] && !$row["is_tuesday"] && !$row["is_wednesday"] && !$row["is_thursday"] && !$row["is_friday"] && !$row["is_saturday"] && !$row["is_sunday"]){
						$working_day = "없음";
					}

					$data["working_day"] = $working_day;
				}

				// 매장 위치
				$enc_artist_id = $crypto->encode(trim($r_customer_id), $access_key, $secret_key);
				$sql = "
					SELECT * 
					FROM tb_request_artist 
					WHERE customer_id = '".$enc_artist_id."'
				";
				$result = mysqli_query($connection,$sql);
				while($row = mysqli_fetch_assoc($result)){
					if ($row["is_got_offline_shop"] == 1) {
						$shop_address = $crypto->decode($row["offline_shop_address"], $access_key, $secret_key);
						if ($shop_address) {
							$data["shop_address"] = str_replace("|", "", strstr($shop_address, "|"));
						} else {
							$data["shop_address"] = "(주소 표기 오류)";
						}
					}
				}
				
				$data["total_service"] = substr($total_service, 0, -1);
				$return_data = array("code" => "000000", "data" => $data, "post" => $_POST);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.", "post" => $_POST);
			}
		}else if($r_mode == "get_counsel_cnt"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			if($r_artist_id != ""){
				$sql = "
					SELECT count(tpl.payment_log_seq) AS wait_count
					FROM tb_payment_log AS tpl 
						INNER JOIN tb_mypet AS tm ON tpl.pet_seq = tm.pet_seq
						INNER JOIN tb_customer AS tc ON tpl.customer_id = tc.id
					WHERE tpl.approval = 0
						AND timestampdiff(minute, tpl.update_time, now()) < 720
						AND tpl.artist_id = '".$r_artist_id."'
				";
				$result = mysqli_query($connection,$sql);
				$row = mysqli_fetch_assoc($result);

				$return_data = array("code" => "000000", "data" => $row["wait_count"], "post" => $_POST);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.", "post" => $_POST);
			}
		}else if($r_mode == "get_cart_cnt"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$where_qy = "";

			if($r_customer_id != ""){
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}

			$sql = "
				SELECT *
				FROM tb_item_cart
				WHERE is_delete = '1'
					AND session_id = '".$sessionid."'
					".$where_qy."
			";
			$result = mysqli_query($connection,$sql);
			$cnt = mysqli_num_rows($result);

			$return_data = array("code" => "000000", "data" => $cnt, "post" => $_POST);
		}else if($r_mode == "get_customer"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

			$sql = "
				SELECT * 
				FROM tb_customer AS cu
					LEFT OUTER JOIN tb_shop AS sh ON cu.id = sh.customer_id 
				WHERE cu.id = '".$r_customer_id."'
			";
			$result = mysqli_query($connection,$sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;

			    if ($row["token"] != null && $row["token"] != "") {
					if ($row["is_android"] == 1) {
						$data["is_android"] = "1";
					}
				}

				if ($row["my_shop_flag"] && $row["open_flag"]) {
					$data["is_artist"] = "1"; // 펫샵주
				}else if($row["artist_flag"]){
					$data["is_artist"] = "2"; // 미용사
				}
			}

			$return_data = array("code" => "000000", "data" => $data, "post" => $_POST);
		}else if($r_mode == "get_shop_recommend_list"){
			$crypto = new Crypto();

			$r_limit = ($_POST["limit"] && $_POST["limit"] != "")? $_POST["limit"] : "3";

			$sql = "
				SELECT *
				FROM tb_shop
				WHERE is_mainshop_recommend = '1'
				ORDER BY RAND()
				LIMIT 0 , ".$r_limit."
			";
			$result = mysqli_query($connection,$sql);
			while($row = mysqli_fetch_assoc($result)){
				$customer_id = $row["customer_id"];
				$data[$customer_id]["shop"] = $row;
				$total_service = "";

				// 개 시술 여부 확인
				$sql = "
					SELECT *
					FROM tb_product_dog_static
					WHERE customer_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				while($row2 = mysqli_fetch_assoc($result2)){
					if ($row2["second_type"] == "소형견미용") {
						$total_service .= "소형견,";
					} else if ($row2["second_type"] == "중형견미용") {
						$total_service .= "중형견,";
					} else if ($row2["second_type"] == "대형견미용") {
						$total_service .= "대형견,";
					} else if ($row2["second_type"] == "특수견미용") {
						$total_service .= "특수견,";
					}

					if($row2["out_shop_product"] == "1"){
						$data[$customer_id]["out_shop_product"] = "1";
					}
				}
				$data[$customer_id]["product_dog"] = mysqli_num_rows($result2);

				// 고양이 시술 여부 확인
				$sql = "
					SELECT *
					FROM tb_product_cat
					WHERE customer_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				while($row2 = mysqli_fetch_assoc($result2)){
					if($row2["out_shop_product"] == "1"){
						$data[$customer_id]["out_shop_product"] = "1";
					}
				}
				$data[$customer_id]["product_cat"] = mysqli_num_rows($result2);
				if($data[$customer_id]["product_cat"] > 0){
					$total_service .= "고양이,";
				}

				// 매장 / 출장 확인
				$sql = "
					SELECT second_type 
					FROM tb_product_dog_static 
					WHERE out_shop_product = 1 
						AND customer_id = '".$customer_id."'
				UNION ALL
					SELECT second_type 
					FROM tb_product_cat 
					WHERE out_shop_product = 1 
						AND customer_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				$data[$customer_id]["out_count"] = mysqli_num_rows($result2);
				if($data[$customer_id]["out_count"] > 0){
					$total_service .= "출장,";
				}

				$sql = "
					SELECT second_type 
					FROM tb_product_dog_static 
					WHERE in_shop_product = 1 
						AND customer_id = '".$customer_id."'
				UNION ALL
					SELECT second_type 
					FROM tb_product_cat 
					WHERE in_shop_product = 1 
						AND customer_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				$data[$customer_id]["in_count"] = mysqli_num_rows($result2);
				if($data[$customer_id]["in_count"] > 0){
					$total_service .= "매장,";
				}

				// 호텔여부 확인
				$sql = "
					SELECT *
					FROM tb_hotel
					WHERE is_delete = '2'
						AND artist_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				$row2 = mysqli_fetch_assoc($result2);
				if($row2["is_enable"] == "1"){ // 1
					$total_service .= "호텔,";
				}
				
				// 유치원여부 확인
				$sql = "
					SELECT *
					FROM tb_playroom
					WHERE is_delete = '2'
						AND artist_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				$row2 = mysqli_fetch_assoc($result2);
				if($row2["is_enable"] == "1"){ // 1
					$total_service .= "유치원,";
				}

				$data[$customer_id]["total_service"] = substr($total_service, 0, -1);

				// 매장 위치
				$enc_artist_id = $crypto->encode(trim($customer_id), $access_key, $secret_key);
				$sql = "
					SELECT * 
					FROM tb_request_artist 
					WHERE customer_id = '".$enc_artist_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				while($row2 = mysqli_fetch_assoc($result2)){
					if ($row2["is_got_offline_shop"] == 1) {
						$shop_address = $crypto->decode($row2["offline_shop_address"], $access_key, $secret_key);
						if ($shop_address) {
							$data[$customer_id]["shop_address"] = str_replace("|", "", strstr($shop_address, "|"));
						} else {
							$data[$customer_id]["shop_address"] = "(주소 표기 오류)";
						}
					}
				}
			}
			$return_data = array("code" => "000000", "data" => $data, "post" => $_POST);
		}else if($r_mode == "get_shop_new_list"){
			$crypto = new Crypto();

			$r_limit = ($_POST["limit"] && $_POST["limit"] != "")? $_POST["limit"] : "3";

			$sql = "
				SELECT *
				FROM tb_shop
				WHERE is_mainshop_new = '1'
				ORDER BY RAND()
				LIMIT 0 , ".$r_limit."
			";
			$result = mysqli_query($connection,$sql);
			while($row = mysqli_fetch_assoc($result)){
				$customer_id = $row["customer_id"];
				$data[$customer_id]["shop"] = $row;
				$total_service = "";

				// 개 시술 여부 확인
				$sql = "
					SELECT *
					FROM tb_product_dog_static
					WHERE customer_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				while($row2 = mysqli_fetch_assoc($result2)){
					if ($row2["second_type"] == "소형견미용") {
						$total_service .= "소형견,";
					} else if ($row2["second_type"] == "중형견미용") {
						$total_service .= "중형견,";
					} else if ($row2["second_type"] == "대형견미용") {
						$total_service .= "대형견,";
					} else if ($row2["second_type"] == "특수견미용") {
						$total_service .= "특수견,";
					}

					if($row2["out_shop_product"] == "1"){
						$data[$customer_id]["out_shop_product"] = "1";
					}
				}
				$data[$customer_id]["product_dog"] = mysqli_num_rows($result2);

				// 고양이 시술 여부 확인
				$sql = "
					SELECT *
					FROM tb_product_cat
					WHERE customer_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				while($row2 = mysqli_fetch_assoc($result2)){
					if($row2["out_shop_product"] == "1"){
						$data[$customer_id]["out_shop_product"] = "1";
					}
				}
				$data[$customer_id]["product_cat"] = mysqli_num_rows($result2);
				if($data[$customer_id]["product_cat"] > 0){
					$total_service .= "고양이,";
				}

				// 매장 / 출장 확인
				$sql = "
					SELECT second_type 
					FROM tb_product_dog_static 
					WHERE out_shop_product = 1 
						AND customer_id = '".$customer_id."'
				UNION ALL
					SELECT second_type 
					FROM tb_product_cat 
					WHERE out_shop_product = 1 
						AND customer_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				$data[$customer_id]["out_count"] = mysqli_num_rows($result2);
				if($data[$customer_id]["out_count"] > 0){
					$total_service .= "출장,";
				}

				$sql = "
					SELECT second_type 
					FROM tb_product_dog_static 
					WHERE in_shop_product = 1 
						AND customer_id = '".$customer_id."'
				UNION ALL
					SELECT second_type 
					FROM tb_product_cat 
					WHERE in_shop_product = 1 
						AND customer_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				$data[$customer_id]["in_count"] = mysqli_num_rows($result2);
				if($data[$customer_id]["in_count"] > 0){
					$total_service .= "매장,";
				}
				
				// 호텔여부 확인
				$sql = "
					SELECT *
					FROM tb_hotel
					WHERE is_delete = '2'
						AND artist_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				$row2 = mysqli_fetch_assoc($result2);
				if($row2["is_enable"] == "1"){ // 1
					$total_service .= "호텔,";
				}
				
				// 유치원여부 확인
				$sql = "
					SELECT *
					FROM tb_playroom
					WHERE is_delete = '2'
						AND artist_id = '".$customer_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				$row2 = mysqli_fetch_assoc($result2);
				if($row2["is_enable"] == "1"){ // 1
					$total_service .= "유치원,";
				}

				$data[$customer_id]["total_service"] = substr($total_service, 0, -1);

				// 매장 위치
				$enc_artist_id = $crypto->encode(trim($customer_id), $access_key, $secret_key);
				$sql = "
					SELECT * 
					FROM tb_request_artist 
					WHERE customer_id = '".$enc_artist_id."'
				";
				$result2 = mysqli_query($connection,$sql);
				while($row2 = mysqli_fetch_assoc($result2)){
					if ($row2["is_got_offline_shop"] == 1) {
						$shop_address = $crypto->decode($row2["offline_shop_address"], $access_key, $secret_key);
						if ($shop_address) {
							$data[$customer_id]["shop_address"] = str_replace("|", "", strstr($shop_address, "|"));
						} else {
							$data[$customer_id]["shop_address"] = "(주소 표기 오류)";
						}
					}
				}
			}
			$return_data = array("code" => "000000", "data" => $data, "post" => $_POST);
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.", "post" => $_POST);
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.", "post" => $_POST);
	}

	function getTotalService($customerId, $connection) {
		
		$totalService = getDocType($customerId, $connection);
		$totalService .= getCatType($customerId, $connection);
		$totalService .= getService($customerId, $connection);
		$totalService .= getHotel($customerId, $connection);
		$totalService .= getKindergarten($customerId, $connection);
		
		$totalService = substr($totalService, 0, -1);
		
		return $totalService;
	}

	function getDocType($customerId, $connection) {
		$sql = "
			SELECT *
			FROM tb_product_dog_static
			WHERE customer_id = '{$customerId}'
		";
		$total_service = "";
		$result = mysqli_query($connection,$sql);
		while($row = mysqli_fetch_assoc($result)){
			if ($row["second_type"] == "소형견미용") {
				$total_service .= "소형견,";
			} else if ($row["second_type"] == "중형견미용") {
				$total_service .= "중형견,";
			} else if ($row["second_type"] == "대형견미용") {
				$total_service .= "대형견,";
			} else if ($row["second_type"] == "특수견미용") {
				$total_service .= "특수견,";
			}

			// if($row["out_shop_product"] == "1"){
			// 	$data["out_shop_product"] = "1";
			// }
		}
		return $total_service;
	}

	function getCatType($customerId, $connection) {
		// 고양이 시술 여부 확인
		$sql = "
			SELECT *
			FROM tb_product_cat
			WHERE customer_id = '".$customerId."'
		";
		$result = mysqli_query($connection,$sql);
		while($row = mysqli_fetch_assoc($result)){
			if($row["out_shop_product"] == "1"){
				$data["out_shop_product"] = "1";
			}
		}
		$total_service = "";
		$data["product_cat"] = mysqli_num_rows($result);
		if($data["product_cat"] > 0){
			$total_service .= "고양이,";
		}

		return $total_service;
	}

	function getService($customerId, $connection) {
		$total_service = "";
		$sql = "
			SELECT second_type 
			FROM tb_product_dog_static 
			WHERE out_shop_product = 1 
				AND customer_id = '".$customerId."'
		UNION ALL
			SELECT second_type 
			FROM tb_product_cat 
			WHERE out_shop_product = 1 
				AND customer_id = '".$customerId."'
		";
		$result = mysqli_query($connection,$sql);
		$data["out_count"] = mysqli_num_rows($result);
		if($data["out_count"] > 0){
			$total_service .= "출장,";
		}

		$sql = "
			SELECT second_type 
			FROM tb_product_dog_static 
			WHERE in_shop_product = 1 
				AND customer_id = '".$customerId."'
		UNION ALL
			SELECT second_type 
			FROM tb_product_cat 
			WHERE in_shop_product = 1 
				AND customer_id = '".$customerId."'
		";
		$result = mysqli_query($connection,$sql);
		$data["in_count"] = mysqli_num_rows($result);
		if($data["in_count"] > 0){
			$total_service .= "매장,";
		}
		return $total_service;
	}

	function getHotel($customerId, $connection) {
		$sql = "
			SELECT *
			FROM tb_hotel
			WHERE is_delete = '2'
				AND artist_id = '".$customerId."'
		";
		$result2 = mysqli_query($connection,$sql);
		$row2 = mysqli_fetch_assoc($result2);
		$total_service = "";
		if($row2["is_enable"] == "1"){
			$total_service .= "호텔,";
		}
		return $total_service;
	}

	function getKindergarten($customerId, $connection) {
		$sql = "
			SELECT *
			FROM tb_playroom
			WHERE is_delete = '2'
				AND artist_id = '".$customerId."'
		";
		$total_service = "";
		$result2 = mysqli_query($connection,$sql);
		$row2 = mysqli_fetch_assoc($result2);
		if($row2["is_enable"] == "1"){ 
			$total_service .= "유치원,";
		}
		return $total_service;
	}

	function getRating($customerId, $connection) {
		$sql = "
			SELECT AVG(rating) AS avg_rating 
			FROM tb_usage_reviews 
			WHERE artist_id = '".$customerId."' 
				AND is_delete = 0
				AND rating IS NOT NULL
		";
		$result = mysqli_query($connection,$sql);
		$row = mysqli_fetch_assoc($result);
		$rating = substr($row["avg_rating"], 0, 3);
		return ($rating == '') ? "-" : $rating;
	}

	function getCommentCount($customerId, $connection) {
		$sql = "
			SELECT COUNT(review_seq) AS review_cnt 
			FROM tb_usage_reviews 
			WHERE artist_id = '".$customerId."' 
				AND is_delete = 0
		";
		$result = mysqli_query($connection,$sql);
		$review = mysqli_fetch_assoc($result);
		return ($review['review_cnt'] > 100) ? "100+" : $review['review_cnt'];
	}

	function getWorkDate($customerId, $connection) {
		// 영업 시간 및 정기 휴일
		$working_day = "매주 ";
		$sql = "
			SELECT * 
			FROM tb_working_schedule AS tws
				INNER JOIN tb_regular_holiday AS trh ON tws.customer_id = trh.customer_id
			WHERE tws.customer_id = '".$customerId."'
		";
		$result = mysqli_query($connection,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$data["working_start"] = $row["working_start"];
			$data["working_end"] = $row["working_end"];
			$data["rest_public_holiday"] = $row["rest_public_holiday"];

			if($row["is_monday"])	{ $working_day .= " 월요일"; }
			if($row["is_tuesday"])	{ $working_day .= " 화요일"; }
			if($row["is_wednesday"]){ $working_day .= " 수요일"; }
			if($row["is_thursday"])	{ $working_day .= " 목요일"; }
			if($row["is_friday"])	{ $working_day .= " 금요일"; }
			if($row["is_saturday"])	{ $working_day .= " 토요일"; }
			if($row["is_sunday"])	{ $working_day .= " 일요일"; }
            $working_day .= " 휴무";

			if(!$row["is_monday"] && !$row["is_tuesday"] && !$row["is_wednesday"] && !$row["is_thursday"] && !$row["is_friday"] && !$row["is_saturday"] && !$row["is_sunday"]){
				$working_day = "연중무휴";
			}

			$data["working_day"] = $working_day;
		}
		return $data;
	}

	function getShopAddress($customerId, $access_key, $secret_key, $connection) {
		$crypto = new Crypto();
		$enc_artist_id = $crypto->encode(trim($customerId), $access_key, $secret_key);
		$sql = "
			SELECT * 
			FROM tb_request_artist 
			WHERE customer_id = '".$enc_artist_id."'
		";
		$addr = "";
		$result2 = mysqli_query($connection,$sql);
		while($row2 = mysqli_fetch_assoc($result2)){
			if ($row2["is_got_offline_shop"] == 1) {
				$shop_address = $crypto->decode($row2["offline_shop_address"], $access_key, $secret_key);
				if ($shop_address) {
					$addr = str_replace("|", "", strstr($shop_address, "|"));
				} else {
					$addr = "(주소 표기 오류)";
				}
			}
		}
		return $addr;
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>
