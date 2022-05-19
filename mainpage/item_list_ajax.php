<?php
	include "../include/configure.php";
	include "../include/Crypto.class.php";
	include "../include/session.php";
	include "../include/db_connection.php";
	include "../include/php_function.php";

	$curl_error_arr = array();
	$curl_error_arr["1"] = "CURLE_UNSUPPORTED_PROTOCOL"; // libcurl에 전달한 URL이이 libcurl이 지원하지 않는 프로토콜을 사용했습니다. 지원은 사용하지 않은 컴파일 타임 옵션 일 수 있으며, 철자가 틀린 프로토콜 문자열이거나 단지 프로토콜 libcurl에 코드가 없을 수 있습니다.
	$curl_error_arr["2"] = "CURLE_FAILED_INIT"; // 초기 초기화 코드가 실패했습니다. 이것은 내부 오류 또는 문제이거나 초기 단계에서 근본적인 작업을 수행 할 수없는 리소스 문제 일 수 있습니다.
	$curl_error_arr["3"] = "CURLE_URL_MALFORMAT"; // URL 형식이 잘못되었습니다.
	$curl_error_arr["4"] = "CURLE_NOT_BUILT_IN"; // 빌드 시간 결정으로 인해 요청 된 기능, 프로토콜 또는 옵션이이 libcurl에 내장되어 있지 않습니다. 이는 libcurl이 빌드 될 때 기능이나 옵션이 활성화되지 않았거나 명시 적으로 비활성화되지 않았 음을 의미하며이를 작동 시키려면 다시 빌드 된 libcurl을 가져와야합니다.
	$curl_error_arr["5"] = "CURLE_COULDNT_RESOLVE_PROXY"; // 프록시를 확인할 수 없습니다. 지정된 프록시 호스트를 확인할 수 없습니다.
	$curl_error_arr["6"] = "CURLE_COULDNT_RESOLVE_HOST"; // 호스트를 확인할 수 없습니다. 주어진 원격 호스트가 해결되지 않았습니다.
	$curl_error_arr["7"] = "CURLE_COULDNT_CONNECT"; // 호스트 또는 프록시에 connect() 하지 못했습니다.
	$curl_error_arr["8"] = "CURLE_WEIRD_SERVER_REPLY"; // 서버에서 보낸 데이터 libcurl을 구문 분석 할 수 없습니다. 이 오류 코드는 7.51.0 이전에 CURLE_FTP_WEIRD_SERVER_REPLY 로 알려 졌습니다.
	$curl_error_arr["9"] = "CURLE_REMOTE_ACCESS_DENIED"; // URL에 제공된 리소스에 대한 액세스가 거부되었습니다. FTP의 경우 원격 디렉토리로 변경하려고 할 때 발생합니다.
	$curl_error_arr["10"] = "CURLE_FTP_ACCEPT_FAILED"; // 활성 FTP 세션이 사용될 때 서버가 다시 연결되기를 기다리는 동안 제어 연결 등을 통해 오류 코드가 전송되었습니다.
	$curl_error_arr["11"] = "CURLE_FTP_WEIRD_PASS_REPLY"; // FTP 암호를 서버로 보낸 후 libcurl은 적절한 응답을 기대합니다. 이 오류 코드는 예기치 않은 코드가 반환되었음을 나타냅니다.
	$curl_error_arr["12"] = "CURLE_FTP_ACCEPT_TIMEOUT"; // 서버 연결을 기다리는 동안 활성 FTP 세션 동안 CURLOPT_ACCEPTTIMEOUT_MS (또는 내부 기본값) 제한 시간이 만료되었습니다.
	$curl_error_arr["13"] = "CURLE_FTP_WEIRD_PASV_REPLY"; // libcurl은 PASV 또는 EPSV 명령에 대한 응답으로 서버에서 적절한 결과를 가져 오지 못했습니다. 서버에 결함이 있습니다.
	$curl_error_arr["14"] = "CURLE_FTP_WEIRD_227_FORMAT"; // FTP 서버는 PASV 명령에 대한 응답으로 227 줄을 반환합니다. libcurl이 해당 행을 구문 분석하지 못하면이 리턴 코드가 다시 전달됩니다.
	$curl_error_arr["15"] = "CURLE_FTP_CANT_GET_HOST"; // 새 연결에 사용 된 호스트를 조회하는 데 내부 오류가 발생했습니다.
	$curl_error_arr["16"] = "CURLE_HTTP2"; // HTTP2 프레임 계층에서 문제가 발견되었습니다. 이것은 다소 일반적이며 여러 문제 중 하나 일 수 있습니다. 자세한 내용은 오류 버퍼를 참조하십시오.
	$curl_error_arr["17"] = "CURLE_FTP_COULDNT_SET_TYPE"; // 전송 모드를 바이너리 또는 ASCII로 설정하려고 할 때 오류가 발생했습니다.
	$curl_error_arr["18"] = "CURLE_PARTIAL_FILE"; // 파일 전송이 예상보다 짧거나 큽니다. 이는 서버가 예상 전송 크기를 처음보고 한 다음 이전에 제공된 크기와 일치하지 않는 데이터를 제공 할 때 발생합니다.
	$curl_error_arr["19"] = "CURLE_FTP_COULDNT_RETR_FILE"; // 이것은 'RETR'명령에 대한 이상한 응답이거나 0 바이트 전송 완료였습니다.
	$curl_error_arr["21"] = "CURLE_QUOTE_ERROR"; // 사용자 정의 "QUOTE"명령을 원격 서버로 보낼 때 명령 중 하나가 400 이상 (FTP의 경우) 오류 코드를 반환했거나 그렇지 않으면 명령이 성공적으로 완료되지 않았 음을 나타냅니다.
	$curl_error_arr["22"] = "CURLE_HTTP_RETURNED_ERROR"; // CURLOPT_FAILONERROR가 TRUE로 설정되고 HTTP 서버가> = 400 오류 코드를 반환하는 경우에 반환됩니다.
	$curl_error_arr["23"] = "CURLE_WRITE_ERROR"; // 수신 된 데이터를 로컬 파일에 쓸 때 오류가 발생했거나 쓰기 콜백에서 libcurl로 오류가 리턴되었습니다.
	$curl_error_arr["25"] = "CURLE_UPLOAD_FAILED"; // 업로드를 시작하지 못했습니다. FTP의 경우 서버는 일반적으로 STOR 명령을 거부했습니다. 오류 버퍼에는 일반적으로 이에 대한 서버의 설명이 포함됩니다.
	$curl_error_arr["26"] = "CURLE_READ_ERROR"; // 로컬 파일을 읽는 데 문제가 있거나 읽기 콜백에서 오류가 반환되었습니다.
	$curl_error_arr["27"] = "CURLE_OUT_OF_MEMORY"; // 메모리 할당 요청이 실패했습니다. 이것은 심각한 나쁜 점이며 이것이 발생하면 상황이 심각하게 망가집니다.
	$curl_error_arr["28"] = "CURLE_OPERATION_TIMEDOUT"; // 작업 시간이 초과되었습니다. 조건에 따라 지정된 제한 시간에 도달했습니다.
	$curl_error_arr["30"] = "CURLE_FTP_PORT_FAILED"; // FTP PORT 명령이 오류를 반환했습니다. 이것은 libcurl이 사용할 충분한 주소를 지정하지 않았을 때 주로 발생합니다. CURLOPT_FTPPORT를 참조 하십시오 .
	$curl_error_arr["31"] = "CURLE_FTP_COULDNT_USE_REST"; // FTP REST 명령이 오류를 반환했습니다. 서버가 정상이면 이런 일이 발생해서는 안됩니다.
	$curl_error_arr["33"] = "CURLE_RANGE_ERROR"; // 서버가 범위 요청을 지원하거나 수락하지 않습니다.
	$curl_error_arr["34"] = "CURLE_HTTP_POST_ERROR"; // 이것은 주로 내부 혼란으로 인해 발생하는 이상한 오류입니다.
	$curl_error_arr["35"] = "CURLE_SSL_CONNECT_ERROR"; // SSL / TLS 핸드 셰이크에서 문제가 발생했습니다. 당신은 정말로 오류 버퍼를 원하고 문제를 약간 더 정확하게 지적하므로 거기에서 메시지를 읽습니다. 인증서 (파일 형식, 경로, 권한), 암호 등이 될 수 있습니다.
	$curl_error_arr["36"] = "CURLE_BAD_DOWNLOAD_RESUME"; // 지정된 오프셋이 파일 경계를 벗어 났기 때문에 다운로드를 재개 할 수 없습니다.
	$curl_error_arr["37"] = "CURLE_FILE_COULDNT_READ_FILE"; // FILE:// 로 제공된 파일을 열 수 없습니다. 파일 경로가 기존 파일을 식별하지 못하기 때문일 가능성이 높습니다. 파일 권한을 확인 했습니까?
	$curl_error_arr["38"] = "CURLE_LDAP_CANNOT_BIND"; // LDAP는 바인딩 할 수 없습니다. LDAP 바인딩 작업이 실패했습니다.
	$curl_error_arr["39"] = "CURLE_LDAP_SEARCH_FAILED"; // LDAP 검색에 실패했습니다.
	$curl_error_arr["41"] = "CURLE_FUNCTION_NOT_FOUND"; // 기능을 찾을 수 없습니다. 필요한 zlib 함수를 찾을 수 없습니다.
	$curl_error_arr["42"] = "CURLE_ABORTED_BY_CALLBACK"; // 콜백에 의해 중단되었습니다. 콜백이 libcurl에 "중단"을 반환했습니다.
	$curl_error_arr["43"] = "CURLE_BAD_FUNCTION_ARGUMENT"; // 잘못된 매개 변수로 함수가 호출되었습니다.
	$curl_error_arr["45"] = "CURLE_INTERFACE_FAILED"; // 인터페이스 오류입니다. 지정된 발신 인터페이스를 사용할 수 없습니다. CURLOPT_INTERFACE 를 사용하여 나가는 연결의 소스 IP 주소에 사용할 인터페이스를 설정 합니다.
	$curl_error_arr["47"] = "CURLE_TOO_MANY_REDIRECTS"; // 리디렉션이 너무 많습니다. 리디렉션을 따를 때 libcurl이 최대량에 도달했습니다. CURLOPT_MAXREDIRS로 제한을 설정 하십시오 .
	$curl_error_arr["48"] = "CURLE_UNKNOWN_OPTION"; // libcurl에 전달 된 옵션이 인식되지 않거나 알 수 없습니다. 해당 문서를 참조하십시오. 이것은 libcurl을 사용하는 프로그램의 문제 일 가능성이 높습니다. 오류 버퍼에는 관련된 정확한 옵션에 대한보다 구체적인 정보가 포함될 수 있습니다.
	$curl_error_arr["49"] = "CURLE_TELNET_OPTION_SYNTAX"; // 텔넷 옵션 문자열이 불법적으로 형식화되었습니다.
	$curl_error_arr["52"] = "CURLE_GOT_NOTHING"; // 서버에서 아무 것도 반환되지 않았으며 상황에서 아무것도 얻지 못하는 것은 오류로 간주됩니다.
	$curl_error_arr["53"] = "CURLE_SSL_ENGINE_NOTFOUND"; // 지정된 암호화 엔진을 찾을 수 없습니다.
	$curl_error_arr["54"] = "CURLE_SSL_ENGINE_SETFAILED"; // 선택한 SSL 암호화 엔진을 기본값으로 설정하지 못했습니다!
	$curl_error_arr["55"] = "CURLE_SEND_ERROR"; // 네트워크 데이터를 보내지 못했습니다.
	$curl_error_arr["56"] = "CURLE_RECV_ERROR"; // 네트워크 데이터 수신에 실패했습니다.
	$curl_error_arr["58"] = "CURLE_SSL_CERTPROBLEM"; // 로컬 클라이언트 인증서에 문제가 있습니다.
	$curl_error_arr["59"] = "CURLE_SSL_CIPHER"; // 지정된 암호를 사용할 수 없습니다.
	$curl_error_arr["60"] = "CURLE_PEER_FAILED_VERIFICATION"; // 원격 서버의 SSL 인증서 또는 SSH md5 지문이 정상이 아닌 것으로 간주되었습니다. 이 오류 코드는 7.62.0부터 CURLE_SSL_CACERT와 통합되었습니다. 이전 값은 51이었습니다.
	$curl_error_arr["61"] = "CURLE_BAD_CONTENT_ENCODING"; // 인식 할 수없는 전송 인코딩입니다.
	$curl_error_arr["62"] = "CURLE_LDAP_INVALID_URL"; // 잘못된 LDAP URL입니다.
	$curl_error_arr["63"] = "CURLE_FILESIZE_EXCEEDED"; // 최대 파일 크기를 초과했습니다.
	$curl_error_arr["64"] = "CURLE_USE_SSL_FAILED"; // 요청 된 FTP SSL 수준이 실패했습니다.
	$curl_error_arr["65"] = "CURLE_SEND_FAIL_REWIND"; // 전송 작업을 수행 할 때 curl은 데이터를 되 감아 재전송해야했지만 되감기 작업이 실패했습니다.
	$curl_error_arr["66"] = "CURLE_SSL_ENGINE_INITFAILED"; // SSL 엔진을 시작하지 못했습니다.
	$curl_error_arr["67"] = "CURLE_LOGIN_DENIED"; // 원격 서버가 로그인 컬을 거부했습니다 (7.13.1에 추가됨).
	$curl_error_arr["68"] = "CURLE_TFTP_NOTFOUND"; // TFTP 서버에서 파일을 찾을 수 없습니다.
	$curl_error_arr["69"] = "CURLE_TFTP_PERM"; // TFTP 서버의 권한 문제.
	$curl_error_arr["70"] = "CURLE_REMOTE_DISK_FULL"; // 서버의 디스크 공간이 부족합니다.
	$curl_error_arr["71"] = "CURLE_TFTP_ILLEGAL"; // 잘못된 TFTP 작업입니다.
	$curl_error_arr["72"] = "CURLE_TFTP_UNKNOWNID"; // 알 수없는 TFTP 전송 ID입니다.
	$curl_error_arr["73"] = "CURLE_REMOTE_FILE_EXISTS"; // 파일이 이미 존재하며 덮어 쓰지 않습니다.
	$curl_error_arr["74"] = "CURLE_TFTP_NOSUCHUSER"; // 이 오류는 제대로 작동하는 TFTP 서버에서 반환되지 않아야합니다.
	$curl_error_arr["75"] = "CURLE_CONV_FAILED"; // 문자 변환에 실패했습니다.
	$curl_error_arr["76"] = "CURLE_CONV_REQD"; // 호출자는 전환 콜백을 등록해야합니다.
	$curl_error_arr["77"] = "CURLE_SSL_CACERT_BADFILE"; // SSL CA 인증서 읽기 문제 (경로? 액세스 권한?)
	$curl_error_arr["78"] = "CURLE_REMOTE_FILE_NOT_FOUND"; // URL에서 참조 된 리소스가 존재하지 않습니다.
	$curl_error_arr["79"] = "CURLE_SSH"; // SSH 세션 중에 지정되지 않은 오류가 발생했습니다.
	$curl_error_arr["80"] = "CURLE_SSL_SHUTDOWN_FAILED"; // SSL 연결을 종료하지 못했습니다.
	$curl_error_arr["81"] = "CURLE_AGAIN"; // 소켓이 송신 / 수신 할 준비가되지 않았습니다. 준비가 될 때까지 기다렸다가 다시 시도하십시오. 이 반환 코드는 curl_easy_recv 및 curl_easy_send 에서만 반환됩니다 (7.18.2에 추가됨).
	$curl_error_arr["82"] = "CURLE_SSL_CRL_BADFILE"; // CRL 파일로드 실패 (7.19.0에 추가됨)
	$curl_error_arr["83"] = "CURLE_SSL_ISSUER_ERROR"; // 발급자 확인 실패 (7.19.0에 추가됨)
	$curl_error_arr["84"] = "CURLE_FTP_PRET_FAILED"; // FTP 서버가 PRET 명령을 전혀 이해하지 못하거나 주어진 인수를 지원하지 않습니다. CURLOPT_CUSTOMREQUEST 를 사용할 때 주의 하십시오 . 사용자 지정 LIST 명령은 PASV 전에 PRET CMD와 함께 전송됩니다. (7.20.0에 추가됨)
	$curl_error_arr["85"] = "CURLE_RTSP_CSEQ_ERROR"; // RTSP CSeq 번호가 일치하지 않습니다.
	$curl_error_arr["86"] = "CURLE_RTSP_SESSION_ERROR"; // RTSP 세션 식별자가 일치하지 않습니다.
	$curl_error_arr["87"] = "CURLE_FTP_BAD_FILE_LIST"; // FTP 파일 목록을 구문 분석 할 수 없습니다 (FTP 와일드 카드 다운로드 중).
	$curl_error_arr["88"] = "CURLE_CHUNK_FAILED"; // 청크 콜백에서 오류를 보고 했습니다.
	$curl_error_arr["89"] = "CURLE_NO_CONNECTION_AVAILABLE"; // (내부 용으로 만 사용되며 libcurl에서 반환되지 않음) 사용 가능한 연결이 없으면 세션이 대기열에 추가됩니다. (7.30.0에 추가됨)
	$curl_error_arr["90"] = "CURLE_SSL_PINNEDPUBKEYNOTMATCH"; // CURLOPT_PINNEDPUBLICKEY로 지정된 고정 키를 일치 시키지 못했습니다 .
	$curl_error_arr["91"] = "CURLE_SSL_INVALIDCERTSTATUS"; // CURLOPT_SSL_VERIFYSTATUS로 요청했을 때 상태가 실패를 반환했습니다 .
	$curl_error_arr["92"] = "CURLE_HTTP2_STREAM"; // HTTP/2 프레임 레이어의 스트림 오류입니다.
	$curl_error_arr["93"] = "CURLE_RECURSIVE_API_CALL"; // 콜백 내부에서 API 함수가 호출되었습니다.
	$curl_error_arr["94"] = "CURLE_AUTH_ERROR"; // 인증 기능이 오류를 반환했습니다.
	$curl_error_arr["95"] = "CURLE_HTTP3"; // HTTP/3 계층에서 문제가 발견되었습니다. 이것은 다소 일반적이며 여러 문제 중 하나 일 수 있습니다. 자세한 내용은 오류 버퍼를 참조하십시오.
	$curl_error_arr["96"] = "CURLE_QUIC_CONNECT_ERROR"; // QUIC 연결 오류입니다. 이 오류는 SSL 라이브러리 오류로 인해 발생할 수 있습니다. QUIC는 HTTP/3 전송에 사용되는 프로토콜입니다.

	$data = array();
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode){
		if($r_mode == "get_item_list"){							// 0001 상품리스트 가져오기
			$r_product_no	   = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
			$r_search_cate	   = ($_POST["search_cate"] && $_POST["search_cate"] != "")? $_POST["search_cate"] : "";
			$r_search_supp	   = ($_POST["search_supp"] != "")? $_POST["search_supp"] : "";
			$r_search_word	   = ($_POST["search_word"] && $_POST["search_word"] != "")? $_POST["search_word"] : "";
			$r_flag			   = ($_POST["flag"] && $_POST["flag"] != "")? $_POST["flag"] : "0";
			$r_cnt			   = ($_POST["cnt"] && $_POST["cnt"] != "")? $_POST["cnt"] : "10";
			$img_list = "";
			$where_qy = "";

			if($r_product_no != ""){
				$where_qy .= " AND product_no = '".$r_product_no."' ";
			}
			if($r_search_cate != ""){
				$cate_list = array();
				$sql = "
					SELECT * 
					FROM tb_item_category 
					WHERE (ic_seq = '".$r_search_cate."' OR node_path LIKE '%".$r_search_cate."%')
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$cate_list[] = $row['ic_seq'];
				}
				$where_qy .= " AND ic_seq REGEXP '".implode('|', $cate_list)."' ";
			}
			if($r_search_supp != ""){
				$where_qy .= " AND is_supply = '".$r_search_supp."' ";
			}
			if($r_search_word != ""){
				$where_qy .= " AND (product_no like '%".$r_search_word."%' OR product_name like '%".$r_search_word."%' OR brand like '%".$r_search_word."%') ";
			}

			$sql = "
				SELECT *
				FROM tb_item_list
				WHERE is_delete != '2'
					".$where_qy."
			";
			$result = mysql_query($sql);
			$data['total_cnt'] = mysql_num_rows($result);

			$sql = "
				SELECT *
				FROM tb_item_list
				WHERE is_delete != '2'
					".$where_qy."
				ORDER BY il_seq DESC
				LIMIT ".$r_flag.", ".$r_cnt."
			";
			$result = mysql_query($sql);
			$data['list_cnt'] = $r_flag + mysql_num_rows($result);
			while($row = mysql_fetch_assoc($result)){
				$data["list"][] = $row;
				$img_list = $row["product_img"];

				if($img_list != ""){
					if(strpos($img_list, ",") !== false){
						$img_list = explode(",", $img_list)[0];
					}

					if($img_list != ""){
						$sql = "
							SELECT *
							FROM tb_file
							WHERE is_delete != '2'
								AND f_seq = '".$img_list."'
						";
						$result2 = mysql_query($sql);
						$file_row = mysql_fetch_assoc($result2);
						$data["file"][$row["il_seq"]] = $file_row["file_path"];
					}
				}
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode ==	"get_item"){						// 0002 상품 가져오기
			$r_il_seq		   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
			$r_product_no	   = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
			$where_qy = "";

			if($r_il_seq != "" || $r_product_no != ""){
				if($r_il_seq != ""){
					$where_qy .= " AND il_seq = '".$r_il_seq."' ";
				}
				if($r_product_no != ""){
					$where_qy .= " AND product_no = '".$r_product_no."' ";
				}

				$sql = "
					SELECT *
					FROM tb_item_list
					WHERE is_delete != '2' ".$where_qy."
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000201", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_insert_item"){					// 0003 상품생성
			$r_ic_seq		   = ($_POST["ic_seq"] && $_POST["ic_seq"] != "")? $_POST["ic_seq"] : "";
			$r_product_no	   = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
			$r_product_name    = ($_POST["product_name"] && $_POST["product_name"] != "")? $_POST["product_name"] : "";
			$r_supplier_price  = ($_POST["supplier_price"] && $_POST["supplier_price"] != "")? $_POST["supplier_price"] : "0";
			$r_product_price   = ($_POST["product_price"] && $_POST["product_price"] != "")? $_POST["product_price"] : "0";
			$r_sale_price	   = ($_POST["sale_price"] && $_POST["sale_price"] != "")? $_POST["sale_price"] : "0";
			$r_product_img     = ($_POST["product_img"] && $_POST["product_img"] != "")? $_POST["product_img"] : "";
			$r_supplier		   = ($_POST["supplier"] && $_POST["supplier"] != "")? $_POST["supplier"] : "";
			$r_is_soldout      = ($_POST["is_soldout"] != "")? $_POST["is_soldout"] : "1";
			$r_is_view		   = ($_POST["is_view"] != "")? $_POST["is_view"] : "2";
			$r_is_use_point    = ($_POST["is_use_point"] != "")? $_POST["is_use_point"] : "2";
			$r_is_use_option   = ($_POST["is_use_option"] != "")? $_POST["is_use_option"] : "2";
			$r_is_view_main_1  = ($_POST["is_view_main_1"] != "")? $_POST["is_view_main_1"] : "2";
			$r_is_view_main_2  = ($_POST["is_view_main_2"] != "")? $_POST["is_view_main_2"] : "2";
			$r_is_view_main_3  = ($_POST["is_view_main_3"] != "")? $_POST["is_view_main_3"] : "2";
			$r_is_view_main_4  = ($_POST["is_view_main_4"] != "")? $_POST["is_view_main_4"] : "2";
			$r_is_view_main_5  = ($_POST["is_view_main_5"] != "")? $_POST["is_view_main_5"] : "2";
			$r_is_view_main_6  = ($_POST["is_view_main_6"] != "")? $_POST["is_view_main_6"] : "2";
			$r_md_name		   = ($_POST["md_name"] && $_POST["md_name"] != "")? $_POST["md_name"] : "";
			$r_md_icon		   = ($_POST["md_icon"] && $_POST["md_icon"] != "")? $_POST["md_icon"] : "";
			$r_md_comment	   = ($_POST["md_comment"] && $_POST["md_comment"] != "")? $_POST["md_comment"] : "";
			$r_product_comment = ($_POST["product_comment"] && $_POST["product_comment"] != "")? $_POST["product_comment"] : "";
			$r_product_detail  = ($_POST["product_detail"] && $_POST["product_detail"] != "")? $_POST["product_detail"] : "";

			$sql = "
				INSERT INTO tb_item_list (
					`ic_seq`, `product_no`, `product_name`, `supplier_price`, `product_price`, 
					`sale_price`, `product_img`, `supplier`, `is_soldout`, `is_view`, 
					`is_use_point`, `is_use_option`, `is_view_main_1`, `is_view_main_2`, `is_view_main_3`, 
					`is_view_main_4`, `is_view_main_5`, `is_view_main_6`, `md_name`, `md_icon`, 
					`md_comment`, `product_comment`, `product_detail`
				) VALUES (
					'".$r_ic_seq."', '".$r_product_no."', '".$r_product_name."', '".$r_supplier_price."', '".$r_product_price."', 
					'".$r_sale_price."', '".$r_product_img."', '".$r_supplier."', '".$r_is_soldout."', '".$r_is_view."', 
					'".$r_is_use_point."', '".$r_is_use_option."', '".$r_is_view_main_1."', '".$r_is_view_main_2."', '".$r_is_view_main_3."', 
					'".$r_is_view_main_4."', '".$r_is_view_main_5."', '".$r_is_view_main_6."', '".addslashes($r_md_name)."', '".$r_md_icon."', 
					'".addslashes($r_md_comment)."', '".$r_product_comment."', '".$r_product_detail."'
				)
			";
			$result = mysql_query($sql);

			if($result){
				$item_seq = mysql_insert_id();
				$return_data = array("code" => "000000", "data" => $item_seq);
			}else{
				$return_data = array("code" => "000301", "data" => "상품 생성에 실패했습니다.");
			}
		}else if($r_mode == "set_update_item"){					// 0004 상품변경
			$r_il_seq		   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
			$r_ic_seq		   = ($_POST["ic_seq"] && $_POST["ic_seq"] != "")? $_POST["ic_seq"] : "";
			$r_product_no	   = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
			$r_product_name    = ($_POST["product_name"] && $_POST["product_name"] != "")? $_POST["product_name"] : "";
			$r_supplier_price  = ($_POST["supplier_price"] != "")? $_POST["supplier_price"] : "0";
			$r_product_price   = ($_POST["product_price"] != "")? $_POST["product_price"] : "0";
			$r_sale_price	   = ($_POST["sale_price"] != "")? $_POST["sale_price"] : "0";
			$r_product_img     = ($_POST["product_img"] && $_POST["product_img"] != "")? $_POST["product_img"] : "";
			$r_supplier		   = ($_POST["supplier"] && $_POST["supplier"] != "")? $_POST["supplier"] : "";
			$r_is_soldout      = ($_POST["is_soldout"] != "")? $_POST["is_soldout"] : "1";
			$r_is_view		   = ($_POST["is_view"] != "")? $_POST["is_view"] : "2";
			$r_is_use_point    = ($_POST["is_use_point"] != "")? $_POST["is_use_point"] : "2";
			$r_is_use_option   = ($_POST["is_use_option"] != "")? $_POST["is_use_option"] : "2";
			$r_is_view_main_1  = ($_POST["is_view_main_1"] != "")? $_POST["is_view_main_1"] : "2";
			$r_is_view_main_2  = ($_POST["is_view_main_2"] != "")? $_POST["is_view_main_2"] : "2";
			$r_is_view_main_3  = ($_POST["is_view_main_3"] != "")? $_POST["is_view_main_3"] : "2";
			$r_is_view_main_4  = ($_POST["is_view_main_4"] != "")? $_POST["is_view_main_4"] : "2";
			$r_is_view_main_5  = ($_POST["is_view_main_5"] != "")? $_POST["is_view_main_5"] : "2";
			$r_is_view_main_6  = ($_POST["is_view_main_6"] != "")? $_POST["is_view_main_6"] : "2";
			$r_md_name		   = ($_POST["md_name"] && $_POST["md_name"] != "")? $_POST["md_name"] : "";
			$r_md_icon		   = ($_POST["md_icon"] && $_POST["md_icon"] != "")? $_POST["md_icon"] : "";
			$r_md_comment	   = ($_POST["md_comment"] && $_POST["md_comment"] != "")? $_POST["md_comment"] : "";
			$r_product_comment = ($_POST["product_comment"] && $_POST["product_comment"] != "")? $_POST["product_comment"] : "";
			$r_product_detail  = ($_POST["product_detail"] && $_POST["product_detail"] != "")? $_POST["product_detail"] : "";
    
			$sql = "
				UPDATE tb_item_list SET
					`ic_seq` = '".$r_ic_seq."', 
					`product_no` = '".$r_product_no."', 
					`product_name` = '".addslashes($r_product_name)."', 
					`supplier_price` = '".$r_supplier_price."', 
					`product_price` = '".$r_product_price."', 
					`sale_price` = '".$r_sale_price."', 
					`product_img` = '".$r_product_img."', 
					`supplier` = '".$r_supplier."', 
					`is_soldout` = '".$r_is_soldout."', 
					`is_view` = '".$r_is_view."', 
					`is_use_point` = '".$r_is_use_point."',
					`is_use_option` = '".$r_is_use_option."',
					`is_view_main_1` = '".$r_is_view_main_1."', 
					`is_view_main_2` = '".$r_is_view_main_2."', 
					`is_view_main_3` = '".$r_is_view_main_3."', 
					`is_view_main_4` = '".$r_is_view_main_4."', 
					`is_view_main_5` = '".$r_is_view_main_5."', 
					`is_view_main_6` = '".$r_is_view_main_6."', 
					`md_name` = '".addslashes($r_md_name)."', 
					`md_icon` = '".$r_md_icon."', 
					`md_comment` = '".addslashes($r_md_comment)."', 
					`product_comment` = '".$r_product_comment."', 
					`product_detail` = '".$r_product_detail."',
					`update_dt` = NOW()
				WHERE il_seq = '".$r_il_seq."'
			";
			$result = mysql_query($sql);

			if($result){
				$return_data = array("code" => "000000", "data" => "OK");
			}else{
				$return_data = array("code" => "000401", "data" => "상품 변경에 실패했습니다.".$sql);
			}
		}else if($r_mode == "set_update_item_img"){				// 0005 상품이미지변경
			$r_il_seq		   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
			$r_product_img     = ($_POST["product_img"] && $_POST["product_img"] != "")? $_POST["product_img"] : "";

			$sql = "
				UPDATE tb_item_list SET
					`product_img` = '".$r_product_img."', 
					`update_dt` = NOW()
				WHERE il_seq = '".$r_il_seq."'
			";
			$result = mysql_query($sql);

			if($result){
				$return_data = array("code" => "000000", "data" => "OK");
			}else{
				$return_data = array("code" => "000501", "data" => "상품 변경에 실패했습니다.");
			}
		}else if($r_mode == "set_delete_item"){					// 0006 상품삭제
			$r_il_seq		   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
			$r_user_id		   = ($_POST["user_id"] && $_POST["user_id"] != "")? $_POST["user_id"] : "";
			$r_delete_txt	   = ($_POST["delete_txt"] && $_POST["delete_txt"] != "")? $_POST["delete_txt"] : "";
			
			$sql = "
				UPDATE tb_item_list SET
					`is_delete` = '2', 
					`delete_txt` = '".$r_user_id."|".$r_delete_txt."', 
					`delete_dt` = NOW()
				WHERE il_seq = '".$r_il_seq."'
			";
			$result = mysql_query($sql);

			if($result){
				$return_data = array("code" => "000000", "data" => "OK");
			}else{
				$return_data = array("code" => "000601", "data" => "상품 삭제에 실패했습니다.");
			}
		}else if($r_mode == "get_item_option"){					// 0007 상품옵션 가져오기
			$r_il_seq		   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";

			$sql = "
				SELECT *
				FROM tb_item_option
				WHERE is_delete != '2'
					AND il_seq = '".$r_il_seq."'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_insert_item_option"){			// 0008 상품옵션생성
			$r_il_seq		   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
			$r_product_no	   = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
			$r_option_name	   = ($_POST["option_name"] && $_POST["option_name"] != "")? $_POST["option_name"] : "";
			$r_supplier_price  = ($_POST["supplier_price"] && $_POST["supplier_price"] != "")? $_POST["supplier_price"] : "0";
			$r_option_price	   = ($_POST["option_price"] && $_POST["option_price"] != "")? $_POST["option_price"] : "0";
			$r_sale_price	   = ($_POST["sale_price"] && $_POST["sale_price"] != "")? $_POST["sale_price"] : "0";
			$r_is_soldout	   = ($_POST["is_soldout"] != "")? $_POST["is_soldout"] : "1";
			$r_is_view		   = ($_POST["is_view"] != "")? $_POST["is_view"] : "1";
			$r_is_free_shipping = ($_POST["is_free_shipping"] != "")? $_POST["is_free_shipping"] : "2";

			$sql = "
				INSERT INTO tb_item_option (
					`il_seq`, `product_no`, `option_name`, `supplier_price`, `option_price`, 
					`sale_price`, `is_soldout`, `is_view`, `is_free_shipping`
				) VALUES (
					'".$r_il_seq."', '".$r_product_no."', '".$r_option_name."', '".$r_supplier_price."', '".$r_option_price."', 
					'".$r_sale_price."', '".$r_is_soldout."', '".$r_is_view."', '".$r_is_free_shipping."'
				)
			";
			$result = mysql_query($sql);

			if($result){
				$item_option_seq = mysql_insert_id();
				$return_data = array("code" => "000000", "data" => $item_option_seq);
			}else{
				$return_data = array("code" => "000801", "data" => "상품 생성에 실패했습니다.");
			}
		}else if($r_mode == "set_update_item_option"){			// 0009 상품옵션수정
			$r_io_seq		   = ($_POST["io_seq"] && $_POST["io_seq"] != "")? $_POST["io_seq"] : "";
			$r_il_seq		   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
			$r_product_no	   = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
			$r_option_name	   = ($_POST["option_name"] && $_POST["option_name"] != "")? $_POST["option_name"] : "";
			$r_supplier_price  = ($_POST["supplier_price"] && $_POST["supplier_price"] != "")? $_POST["supplier_price"] : "0";
			$r_option_price	   = ($_POST["option_price"] && $_POST["option_price"] != "")? $_POST["option_price"] : "0";
			$r_sale_price	   = ($_POST["sale_price"] && $_POST["sale_price"] != "")? $_POST["sale_price"] : "0";
			$r_is_soldout	   = ($_POST["is_soldout"] != "")? $_POST["is_soldout"] : "1";
			$r_is_view		   = ($_POST["is_view"] != "")? $_POST["is_view"] : "1";
			$r_is_free_shipping = ($_POST["is_free_shipping"] != "")? $_POST["is_free_shipping"] : "2";

			$sql = "
				UPDATE tb_item_option SET
					`il_seq` = '".$r_il_seq."', 
					`product_no` = '".$r_product_no."', 
					`option_name` = '".$r_option_name."', 
					`supplier_price` = '".$r_supplier_price."', 
					`option_price` = '".$r_option_price."', 
					`sale_price` = '".$r_sale_price."', 
					`is_soldout` = '".$r_is_soldout."', 
					`is_view` = '".$r_is_view."',
					`is_free_shipping` = '".$r_is_free_shipping."',
					`update_dt` = NOW()
				WHERE io_seq = '".$r_io_seq."'
			";
			$result = mysql_query($sql);

			if($result){
				$item_option_seq = mysql_insert_id();
				$return_data = array("code" => "000000", "data" => $item_option_seq);
			}else{
				$return_data = array("code" => "000901", "data" => "상품 변경에 실패했습니다.");
			}		
		}else if($r_mode == "set_delete_item_option"){			// 0010 상품옵션삭제
			$r_io_seq		   = ($_POST["io_seq"] && $_POST["io_seq"] != "")? $_POST["io_seq"] : "";
			$r_user_id		   = ($_POST["user_id"] && $_POST["user_id"] != "")? $_POST["user_id"] : "";
			$r_delete_txt	   = ($_POST["delete_txt"] && $_POST["delete_txt"] != "")? $_POST["delete_txt"] : "";
			
			$sql = "
				UPDATE tb_item_option SET
					`is_delete` = '2', 
					`delete_txt` = '".$r_user_id."|".$r_delete_txt."', 
					`delete_dt` = NOW()
				WHERE io_seq = '".$r_io_seq."'
			";
			$result = mysql_query($sql);

			if($result){
				$return_data = array("code" => "000000", "data" => "OK");
			}else{
				$return_data = array("code" => "001001", "data" => "상품 삭제에 실패했습니다.");
			}
		}else if($r_mode == "get_cate1"){						// 0011 상품카테고리(대분류)
			$sql = "
				SELECT *
				FROM tb_item_category
				WHERE depth = '0'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_cate2"){						// 0012 상품카테고리(중분류)
			$r_cate1		   = ($_POST["cate1"] && $_POST["cate1"] != "")? $_POST["cate1"] : "";
			if($r_cate1 != ""){
				$where_qy = " AND parent_seq = '".$r_cate1."' ";
			}else{
				$where_qy = "";
			}

			$sql = "
				SELECT *
				FROM tb_item_category
				WHERE depth = '1'
					".$where_qy."
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_cate3"){						// 0013 상품카테고리(소분류)
			$r_cate2		   = ($_POST["cate2"] && $_POST["cate2"] != "")? $_POST["cate2"] : "";
			$where_qy = "";

			if($r_cate2 != ""){
				$where_qy .= " AND parent_seq = '".$r_cate2."' ";
			}

			$sql = "
				SELECT *
				FROM tb_item_category
				WHERE depth = '2'
					".$where_qy."
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_cate_list"){					// 0014 상품카테고리리스트 가져오기
			$r_cate 		   = ($_POST["cate"] && $_POST["cate"] != "")? $_POST["cate"] : "";

			if($r_cate != ""){
				$where_qy = "";

				if(strpos($r_cate, ",")){
					$where_qy .= " AND ic_seq in (";
					$r_cate = explode(",", $r_cate);

					for($_i = 0; $_i < count($r_cate); $_i++){
						$where_qy .= "'".$r_cate[$_i]."',";
					}
					$where_qy = substr($where_qy, 0, -1);
					$where_qy .= ")";
				}else{
					$where_qy .= " AND ic_seq = '".$r_cate."' ";
				}

				$sql = "
					SELECT *
					FROM tb_item_category
					WHERE 1=1 ".$where_qy."
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "001401", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_insert_cart"){					// 0015 상품카트생성
			$r_total_price	   = ($_POST["total_price"] && $_POST["total_price"] != "")? $_POST["total_price"] : "";
			$r_user_id 		   = ($_POST["user_id"] && $_POST["user_id"] != "")? $_POST["user_id"] : "";
			$r_cart_data	   = ($_POST["cart_data"] && $_POST["cart_data"] != "")? $_POST["cart_data"] : "";
			$r_product_no	   = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
			$order_num = "I".STRTOTIME(DATE("Y-m-d H:i:s")).str_pad(rand(0,99),"2","0",STR_PAD_LEFT);

			$sql = "
				INSERT INTO tb_item_cart (
					`product_no`, `order_num`, `customer_id`, `cart_price`, `cart_data`, 
					`reg_dt`
				) VALUES (
					'".$r_product_no."', '".$order_num."', '".$r_user_id."', '".$r_total_price."', '".addslashes($r_cart_data)."', 
					NOW()
				)
			";
			$result = mysql_query($sql);

			if($result){
				$return_data = array("code" => "000000", "data" => $order_num);
			}else{
				$return_data = array("code" => "001501", "data" => "배송지 생성에 실패했습니다.");
			}
		}else if($r_mode == "set_delete_item_cart"){			// 0016 상품카트삭제
			$r_ic_seq		   = ($_POST["ic_seq"] && $_POST["ic_seq"] != "")? $_POST["ic_seq"] : "";
			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_user_id		   = ($_POST["user_id"] && $_POST["user_id"] != "")? $_POST["user_id"] : "";
			$r_delete_txt	   = ($_POST["delete_txt"] && $_POST["delete_txt"] != "")? $_POST["delete_txt"] : "";
			$where_qy = "";

			if($r_ic_seq != "" || $r_order_num != ""){
				if($r_ic_seq != ""){
					$where_qy = " AND ic_seq = '".$r_ic_seq."' ";
				}
				if($r_order_num != ""){
					$where_qy = " AND order_num = '".$r_order_num."' ";
				}

				$sql = "
					UPDATE tb_item_cart SET
						`is_delete` = '2', 
						`delete_txt` = '".$r_user_id."|".$r_delete_txt."', 
						`delete_dt` = NOW()
					WHERE is_delete = '1' ".$where_qy."
				";
				$result = mysql_query($sql);

				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "001601", "data" => "상품 카트 삭제에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "001602", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_cart"){						// 0017 상품카트 가져오기
			$r_no			   = ($_POST["no"] && $_POST["no"] != "")? $_POST["no"] : "";

			$sql = "
				SELECT *
				FROM tb_item_cart
				WHERE order_num = '".$r_no."'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_item_payment_log"){			// 0018 상품결제내역 가져오기
			// add search keyword
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

			$sql = "
				SELECT *
				FROM tb_item_payment_log
				WHERE is_delete != '2'
					AND customer_id = '".$r_customer_id."'
				ORDER BY ip_log_seq DESC
				limit 0, 10
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result)){
				$data[] = $row;
			}
			
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_insert_item_payment_log"){		// 0019 상품결제생성
			include "../include/Allimtalk.class.php"; // 알림톡 Class 호출

			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_customer_name   = ($_POST["customer_name"] && $_POST["customer_name"] != "")? $_POST["customer_name"] : "";
			$r_customer_email  = ($_POST["customer_email"] && $_POST["customer_email"] != "")? $_POST["customer_email"] : "";
			$r_customer_email_suffix = ($_POST["customer_email_suffix"] && $_POST["customer_email_suffix"] != "")? $_POST["customer_email_suffix"] : "";
			$r_product_name	   = ($_POST["product_name"] && $_POST["product_name"] != "")? $_POST["product_name"] : "";
			$r_product_price   = ($_POST["product_price"] && $_POST["product_price"] != "")? $_POST["product_price"] : "0";
			$r_shipping_price  = ($_POST["shipping_price"] && $_POST["shipping_price"] != "")? $_POST["shipping_price"] : "0";
			$r_point_price	   = ($_POST["point_price"] && $_POST["point_price"] != "")? $_POST["point_price"] : "0";
			$r_total_price	   = ($_POST["total_price"] && $_POST["total_price"] != "")? $_POST["total_price"] : "0";
			$r_cellphone	   = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_shipping_name   = ($_POST["shipping_name"] && $_POST["shipping_name"] != "")? $_POST["shipping_name"] : "";
			$r_zipcode		   = ($_POST["zipcode"] && $_POST["zipcode"] != "")? $_POST["zipcode"] : "";
			$r_addr1		   = ($_POST["addr1"] && $_POST["addr1"] != "")? $_POST["addr1"] : "";
			$r_addr2		   = ($_POST["addr2"] && $_POST["addr2"] != "")? $_POST["addr2"] : "";
			$r_addr3		   = ($_POST["addr3"] && $_POST["addr3"] != "")? $_POST["addr3"] : "";
			$r_addr4		   = ($_POST["addr4"] && $_POST["addr4"] != "")? $_POST["addr4"] : "";
			$r_shipping_cellphone = ($_POST["shipping_cellphone"] && $_POST["shipping_cellphone"] != "")? $_POST["shipping_cellphone"] : "";
			$r_shipping_memo   = ($_POST["shipping_memo"] && $_POST["shipping_memo"] != "")? $_POST["shipping_memo"] : "";
			$r_pay_type		   = ($_POST["pay_type"] != "")? $_POST["pay_type"] : "1";
			$r_bank_name	   = ($_POST["bank_name"] && $_POST["bank_name"] != "")? $_POST["bank_name"] : "";

			$guest_info = $r_customer_name.",".$r_customer_email."@".$r_customer_email_suffix;
			$shipping_zipcode = trim($r_zipcode);
			$shipping_addr = trim($r_addr1)." ".trim($r_addr3);
			$shipping_addr2 = trim($r_addr4);
			$expire_dt = "";
			$bank_name = "";
			$add_column1 = "";
			$add_column1_val = "";
			$reg_dt = DATE("Y-m-d H:i:s"); // 입력일시
			$cellphone = (strpos($r_cellphone, "-") !== false)? str_replace("-", "", $r_cellphone) : $r_cellphone;
			$shipping_cellphone = (strpos($r_shipping_cellphone, "-") !== false)? str_replace("-", "", $r_shipping_cellphone) : $r_shipping_cellphone;

			if($r_pay_type == "1"){ // 신용카드
				$add_column1 = ", `pay_dt`, `pg_log`, `pay_status`, `receipt_id` ";
				$add_column1_val = ", '".$reg_dt."', '".$_SESSION["RESPONSE"]."', '3', '".$_SESSION["RECEIPT_ID"]."' "; // 상품준비중
			}else if($r_pay_type == "2"){ // 계좌이체
				$expire_dt = date("Y-m-d H:i:s", getBankDDay(intval(date('H'))));
				$bank_name = $r_bank_name;
				$add_column1 = ", `bank_name`, `expire_dt`, `pay_status` ";
				$add_column1_val = ", '".$bank_name."', '".$expire_dt."', '2' "; // 입금대기중
			}

			$sql = "
				SELECT *
				FROM tb_item_cart
				WHERE order_num = '".$r_order_num."'
			";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			$pay_data = $row["cart_data"];

			$sql = "
				INSERT INTO tb_item_payment_log (
					`order_num`, `customer_id`, `guest_info`, `product_name`, `product_price`, 
					`point_price`, `shipping_price`, `total_price`, `cellphone`, `shipping_name`, 
					`shipping_zipcode`, `shipping_addr`, `shipping_addr2`, `shipping_cellphone`, `shipping_memo`, `pay_type`, `pay_data`, 
					`reg_dt` ".$add_column1."
				) VALUES (
					'".$r_order_num."', '".$r_customer_id."', '".$guest_info."', '".$r_product_name."', '".$r_product_price."', 
					'".$r_point_price."', '".$r_shipping_price."', '".$r_total_price."', '".$cellphone."', '".addslashes($r_shipping_name)."', 
					'".addslashes($shipping_zipcode)."', '".addslashes($shipping_addr)."', '".addslashes($shipping_addr2)."', '".$shipping_cellphone."', '".addslashes($r_shipping_memo)."', 
					'".$r_pay_type."', '".$pay_data."', '".$reg_dt."' ".$add_column1_val."
				)				
			";
			$result = mysql_query($sql);

			if($result){
				$_SESSION["RESPONSE"] = ""; // init
				$_SESSION["RECEIPT_ID"] = ""; // init
				$ip_log_seq = mysql_insert_id();
				if($r_point_price > 0){ // 포인트 결제 사용시 포인트 감소
					include "../include/Point.class.php";
					$point = new Point;
					$result = $point->select_point($r_customer_id);
					$point->spend_point($r_point_price, $ip_log_seq, "product_".$r_order_num);
				}

				// 관리자 푸시 발송
				$pushPath = "https://www.gopet.kr/pet/admin/item_payment_log_detail.php?no=".$r_order_num;
				//$pushImage = "https://www.gopet.kr/pet/images/logo_login.jpg";
				$pushImage = "";
				$pushPayType = ($r_pay_type == "1")? "카드" : "계좌이체";
				$admin_message = substr($cellphone, -4) . "(".$r_customer_email."@".$r_customer_email_suffix.")님이 [".$r_product_name."]을 구매(".$pushPayType."). 상품결제 관리를 확인하세요";
				a_push("pickmon@pickmon.com", "반짝_상품구매알림", $admin_message, $pushPath, $pushImage);

				// 알림톡 발송
				if($r_pay_type == "1"){ // 신용카드
					$talk = new Allimtalk();

					$talk->cellphone = $cellphone;

					$talkCustomerName = substr($cellphone, -4);
					$talkDate = date("y년 m월 d일 H시 i분", STRTOTIME(DATE("Y-m-d H:i:s")));
					$talkBtnLink = "https://gopet.kr/pet/chkodr/?no=".$r_order_num;
					$talkResult = $talk->sendOrderReceipt_new($talkCustomerName, $r_order_num, $talkDate, $r_product_name, $talkBtnLink);
				}else if($r_pay_type == "2"){ // 계좌이체
					$talk = new Allimtalk();

					$talk->cellphone = $cellphone;

					$talkCustomerName = substr($cellphone, -4);
					$talkDate = date("y년 m월 d일 H시 i분", STRTOTIME(DATE("Y-m-d H:i:s")));
					$talkExpireDate = date("y년 m월 d일 H시 i분", STRTOTIME($expire_dt));
					$talkTotalPrice = number_format($r_total_price)."원";
					$talkBtnLink = "https://gopet.kr/pet/chkodr/?no=".$r_order_num;
					$talkResult = $talk->sendOrderAccount_new($talkCustomerName, $r_order_num, $talkDate, $r_product_name, $talkExpireDate, $talkTotalPrice, $r_shipping_name, $talkBtnLink);
				}

				$return_data = array("code" => "000000", "data" => $r_order_num);
			}else{
				$return_data = array("code" => "001901", "data" => "상품 결제 생성에 실패했습니다.");
			}
		}else if($r_mode == "set_update_item_payment_log"){		// 0020 상품결제수정
			$r_ip_log_seq	   = ($_POST["ip_log_seq"] && $_POST["ip_log_seq"] != "")? $_POST["ip_log_seq"] : "";
			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_customer_name   = ($_POST["customer_name"] && $_POST["customer_name"] != "")? $_POST["customer_name"] : "";
			$r_customer_email  = ($_POST["customer_email"] && $_POST["customer_email"] != "")? $_POST["customer_email"] : "";
			$r_customer_email_suffix = ($_POST["customer_email_suffix"] && $_POST["customer_email_suffix"] != "")? $_POST["customer_email_suffix"] : "";
			$r_product_name	   = ($_POST["product_name"] && $_POST["product_name"] != "")? $_POST["product_name"] : "";
			$r_product_price   = ($_POST["product_price"] && $_POST["product_price"] != "")? $_POST["product_price"] : "0";
			$r_shipping_price  = ($_POST["shipping_price"] && $_POST["shipping_price"] != "")? $_POST["shipping_price"] : "0";
			$r_point_price	   = ($_POST["point_price"] && $_POST["point_price"] != "")? $_POST["point_price"] : "0";
			$r_total_price	   = ($_POST["total_price"] && $_POST["total_price"] != "")? $_POST["total_price"] : "0";
			$r_cellphone	   = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_shipping_name   = ($_POST["shipping_name"] && $_POST["shipping_name"] != "")? $_POST["shipping_name"] : "";
			$r_zipcode		   = ($_POST["zipcode"] && $_POST["zipcode"] != "")? $_POST["zipcode"] : "";
			$r_addr1		   = ($_POST["addr1"] && $_POST["addr1"] != "")? $_POST["addr1"] : "";
			$r_addr2		   = ($_POST["addr2"] && $_POST["addr2"] != "")? $_POST["addr2"] : "";
			$r_addr3		   = ($_POST["addr3"] && $_POST["addr3"] != "")? $_POST["addr3"] : "";
			$r_addr4		   = ($_POST["addr4"] && $_POST["addr4"] != "")? $_POST["addr4"] : "";
			$r_shipping_cellphone = ($_POST["shipping_cellphone"] && $_POST["shipping_cellphone"] != "")? $_POST["shipping_cellphone"] : "";
			$r_shipping_memo   = ($_POST["shipping_memo"] && $_POST["shipping_memo"] != "")? $_POST["shipping_memo"] : "";
			$r_pay_type		   = ($_POST["pay_type"] != "")? $_POST["pay_type"] : "1";
			$r_bank_name	   = ($_POST["bank_name"] && $_POST["bank_name"] != "")? $_POST["bank_name"] : "";

			if($r_ip_log_seq != "" && $r_order_num != ""){
				$guest_info = $r_customer_name.",".$r_customer_email."@".$r_customer_email_suffix;
				$shipping_zipcode = trim($r_zipcode);
				$shipping_addr = trim($addr1)." ".trim($addr3);
				$shipping_addr2 = trim($addr4);
				$cellphone = (strpos($r_cellphone, "-") !== false)? str_replace("-", "", $r_cellphone) : $r_cellphone;
				$shipping_cellphone = (strpos($r_shipping_cellphone, "-") !== false)? str_replace("-", "", $r_shipping_cellphone) : $r_shipping_cellphone;

				$sql = "
					SELECT *
					FROM tb_item_cart
					WHERE order_num = '".$r_order_num."'
				";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				$pay_data = $row["cart_data"];

				$sql = "
					UPDATE tb_item_payment_log SET
						`order_num` = '".$r_order_num."', 
						`customer_id` = '".$r_customer_id."', 
						`guest_info` = '".$guest_info."', 
						`product_name` = '".$r_product_name."', 
						`product_price` = '".$r_product_price."', 
						`shipping_price` = '".$r_shipping_price."', 
						`point_price` = '".$r_point_price."', 
						`total_price` = '".$r_total_price."', 
						`cellphone` = '".$cellphone."', 
						`shipping_name` = '".addslashes($r_shipping_name)."', 
						`shipping_zipcode` = '".addslashes($shipping_zipcode)."', 
						`shipping_addr` = '".addslashes($shipping_addr)."', 
						`shipping_addr2` = '".addslashes($shipping_addr2)."', 
						`shipping_cellphone` = '".$shipping_cellphone."', 
						`shipping_memo` = '".addslashes($r_shipping_memo)."', 
						`pay_type` = '".$r_pay_type."', 
						`pay_data` = '".$pay_data."',
						`update_dt` = NOW()
					WHERE order_num = '".$r_order_num."'
						AND ip_log_seq = '".$r_ip_log_seq."'
				";
				$result = mysql_query($sql);

				if($result){
					$return_data = array("code" => "000000", "data" => $r_order_num);
				}else{
					$return_data = array("code" => "002001", "data" => "상품 결제 수정에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "002002", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_delete_item_payment_log"){		// 0021 상품결제삭제
			$r_ip_log_seq	   = ($_POST["ip_log_seq"] && $_POST["ip_log_seq"] != "")? $_POST["ip_log_seq"] : "";
			$r_user_id		   = ($_POST["user_id"] && $_POST["user_id"] != "")? $_POST["user_id"] : "";
			$r_delete_txt	   = ($_POST["delete_txt"] && $_POST["delete_txt"] != "")? $_POST["delete_txt"] : "";
			
			$sql = "
				UPDATE tb_item_payment_log SET
					`is_delete` = '2', 
					`delete_txt` = '".$r_user_id."|".$r_delete_txt."', 
					`delete_dt` = NOW()
				WHERE ip_log_seq = '".$r_ip_log_seq."'
			";
			$result = mysql_query($sql);

			if($result){
				$return_data = array("code" => "000000", "data" => "OK");
			}else{
				$return_data = array("code" => "002101", "data" => "상품 결제 삭제에 실패했습니다.");
			}
		}else if($r_mode == "item_payment_ready"){				// 0022 상품결제준비(PC)
			//결제금액, 상품명, 결제방법, 주문번호(order_num)
			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_product_name	   = ($_POST["product_name"] && $_POST["product_name"] != "")? $_POST["product_name"] : "";
			$r_total_price	   = ($_POST["total_price"] && $_POST["total_price"] != "")? $_POST["total_price"] : "";
			$r_pay_type		   = ($_POST["pay_type"] && $_POST["pay_type"] != "")? $_POST["pay_type"] : "";
			$pay_type_arr = array("", "card", "bank"); // card만 사용예정

			// 데이터 검증용 세션데이터 생성 - 미사용
			$_SESSION["ORDER_NUM"]		= $r_order_num;
			$_SESSION["PRODUCT_NAME"]	= $r_product_name;
			$_SESSION["TOTAL_PRICE"]	= $r_total_price;
			$_SESSION["PAY_TYPE"]		= $r_pay_type;

			$return_data = array("code" => "000000", "data" => array("price" => $r_total_price, "product_name" => $r_product_name, "pay_type" => $pay_type_arr[$r_pay_type]));
		}else if($r_mode == "item_payment_varification"){		// 0023 상품결제검증(PC)
			$r_receipt_id	   = ($_POST["receipt_id"] && $_POST["receipt_id"] != "")? $_POST["receipt_id"] : "";
			$r_shipping_price  = ($_POST["shipping_price"] && $_POST["shipping_price"] != "")? $_POST["shipping_price"] : "";
			$r_point_price  = ($_POST["point_price"] && $_POST["point_price"] != "")? $_POST["point_price"] : "";
			$application_id = "5acc2185b6d49c7b637d9c12";
			$private_key = 'oZq//0OpaSulB2uzNU8l7mQGgQpePEmpihnUb5TuAvA=';
			$url = "https://api.bootpay.co.kr/receipt/".$r_receipt_id."?application_id=".$application_id."&private_key=".$private_key;
			
			$headers = array('Content-Type: application/json');
	        $ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			$errno = curl_errno($ch);
			$errstr = curl_error($ch);

			if ($errno) {
				$response = $response."|".$errno."|".$errstr;
				$return_data = array("code" => "000000", "data" => $response);
			}else{
				$veri_response = json_decode($response);
				$sql = "
					SELECT *
					FROM tb_item_cart
					WHERE order_num = '".$veri_response->data->order_id."'
				";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);

				// status code: 0-준비중, 1-결제성공, 2-결제승인전, 3-결제승인중(PG검토), 20-결제 취소, -20 결제취소 실패, -30 결제취소 진행중, -1 오류로인한 결제실패, -2 결제승인 실패
				if ($veri_response->data->price === (int)($row["cart_price"]+$r_shipping_price-$r_point_price) && $veri_response->data->status === 1) {
					// TODO: 이곳이 상품 지급 혹은 결제 완료 처리를 하는 로직으로 사용하면 됩니다.
					// 결과값 세션처리하여 item_payment_log 입력시 사용
					$_SESSION["RESPONSE"] = $response; 
					$_SESSION["RECEIPT_ID"] = $r_receipt_id;
					$return_data = array("code" => "000000", "data" => $response);
				}else{
					$return_data = array("code" => "002301", "data" => "결제 검증에 실패했습니다.");
				}
			}
		}else if($r_mode == "item_payment_cancel"){				// 0024 상품결제취소
			$r_receipt_id	   = ($_POST["receipt_id"] && $_POST["receipt_id"] != "")? $_POST["receipt_id"] : "";
			$r_customer_name   = ($_POST["customer_name"] && $_POST["customer_name"] != "")? $_POST["customer_name"] : "";
			$r_cancel_reason   = ($_POST["cancel_reason"] && $_POST["cancel_reason"] != "")? $_POST["cancel_reason"] : "";
			$r_cancel_price    = ($_POST["cancel_price"] && $_POST["cancel_price"] != "")? $_POST["cancel_price"] : "";
			$r_cancel_type	   = ($_POST["cancel_type"] && $_POST["cancel_type"] != "")? $_POST["cancel_type"] : "";

			$tid = $r_receipt_id;
			$msg = $r_cancel_reason;
			$price = $r_cancel_price;
			$confirmPrice = "0";

			$return_data = INI_PartialRefund($tid, $msg, $price, $confirmPrice, $curl_error_arr);
		}else if($r_mode == "get_item_order_list"){				// 0025 상품주문내역 가져오기
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_pay_status	   = ($_POST["pay_status"] && $_POST["pay_status"] != "")? $_POST["pay_status"] : "";
			$r_item_list	   = ($_POST["item_list"] && $_POST["item_list"] != "")? $_POST["item_list"] : "";
			$r_customer_name   = ($_POST["customer_name"] && $_POST["customer_name"] != "")? $_POST["customer_name"] : "";
			$r_flag			   = ($_POST["flag"] && $_POST["flag"] != "")? $_POST["flag"] : "0";
			$r_cnt			   = ($_POST["cnt"] && $_POST["cnt"] != "")? $_POST["cnt"] : "20";
			$where_qy = "";
			
			if($r_customer_id != ""){
				$where_qy .= " AND ip.customer_id = '".$r_customer_id."' ";
			}

			if($r_order_num != ""){
				$where_qy .= " AND ip.order_num = '".$r_order_num."' ";
			}

			if($r_pay_status != ""){
				$where_qy .= " AND ip.pay_status = '".$r_pay_status."' ";
			}

			if($r_item_list != ""){
				$where_qy .= " AND ic.product_no = '".$r_item_list."' ";
			}

			if($r_customer_name != ""){
				$where_qy .= " AND SUBSTRING_INDEX(ip.guest_info, ',', 1) like '%".$r_customer_name."%' ";
			}

			$sql = "
				SELECT ip.*, il.product_no, il.product_img
				FROM tb_item_payment_log AS ip
					LEFT OUTER JOIN tb_item_cart AS ic ON ip.order_num = ic.order_num
					LEFT OUTER JOIN tb_item_list AS il ON ic.product_no = il.product_no
				WHERE ip.is_delete != '2' 
					AND il.is_delete != '2'
					".$where_qy."
				ORDER BY ip.ip_log_seq DESC
				LIMIT ".$r_flag.", ".$r_cnt."
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result)){
				$data[] = $row;
			}
			
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_update_pay_status"){			// 0026 상품결제 상태값변경
			include "../include/Allimtalk.class.php";

			$r_pay_status	   = ($_POST["pay_status"] && $_POST["pay_status"] != "")? $_POST["pay_status"] : "";
			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_shipping_invoice = ($_POST["shipping_invoice"] && $_POST["shipping_invoice"] != "")? $_POST["shipping_invoice"] : "";
			$r_shipping_company = ($_POST["shipping_company"] && $_POST["shipping_company"] != "")? $_POST["shipping_company"] : "";
			$r_use_talk		   = ($_POST["use_talk"] && $_POST["use_talk"] != "")? $_POST["use_talk"] : "0";

			$where_qy = "";

			if($r_pay_status == "2"){ // 입금대기
				$where_qy .= ", `pay_status` = '".$r_pay_status."' ";
				$where_qy .= ", `pay_dt` = NULL ";
			}else if($r_pay_status == "3"){ // 상품준비중
				$where_qy .= ", `pay_status` = '".$r_pay_status."' ";
				$where_qy .= ", `pay_dt` = NOW() ";
			}else if($r_pay_status == "4"){ // 배송준비중
				$where_qy .= ", `pay_status` = '".$r_pay_status."' ";
			}else if($r_pay_status == "5"){ // 배송중
				$where_qy .= ", `pay_status` = '".$r_pay_status."' ";
				$where_qy .= ", `shipping_invoice` = '".$r_shipping_invoice."' ";
			}else if($r_pay_status == "6"){ // 배송완료
				$where_qy .= ", `pay_status` = '".$r_pay_status."' ";
				$where_qy .= ", `shipping_dt` = NOW() ";
			}else{
				$where_qy .= ", `pay_status` = '".$r_pay_status."' ";
			}

			if($r_pay_status != "" && $r_order_num != ""){
				$sql = "
					UPDATE tb_item_payment_log SET
						`update_dt` = NOW() ".$where_qy."
					WHERE `order_num` = '".$r_order_num."'
				";
				$result = mysql_query($sql);

				if($result){
					if($r_use_talk == "1" && $r_pay_status == "5" && $r_shipping_invoice != ""){ // 알림톡발송 & 배송중 & 배송번호 입력
						$sql = "
							SELECT *
							FROM tb_item_payment_log
							WHERE order_num = '".$r_order_num."'
						";
						$result = mysql_query($sql);
						$row = mysql_fetch_array($result);

						$talk = new Allimtalk();

						$talk->cellphone = $row['cellphone'];

						$talkCustomerName = substr($row['cellphone'], -4);
						$talkDate = date("y년 m월 d일 H시 i분", STRTOTIME($row['pay_dt']));
						$talkBtnLink = "https://gopet.kr/pet/chkodr/?no=".$r_order_num;
						$talkShippingInvoice = $r_shipping_company." ( ".$r_shipping_invoice." )";
						$talkResult = $talk->sendOrderShipping_new($talkCustomerName, $r_order_num, $row["product_name"], $talkShippingInvoice, $talkBtnLink);
					}
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "002601", "data" => "상품 결제상태 변경에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "002602", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_shipping_invoice"){		// 0027 상품결제 배송번호변경
			$r_shipping_invoice = ($_POST["shipping_invoice"] && $_POST["shipping_invoice"] != "")? $_POST["shipping_invoice"] : "";
			$r_shipping_company = ($_POST["shipping_company"] && $_POST["shipping_company"] != "")? $_POST["shipping_company"] : "";
			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			
			if($r_order_num != ""){
				$sql = "
					UPDATE tb_item_payment_log SET
						`shipping_invoice` = '".$r_shipping_invoice."', 
						`shipping_company` = '".$r_shipping_company."', 
						`update_dt` = NOW()
					WHERE `order_num` = '".$r_order_num."'
				";
				$result = mysql_query($sql);

				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "002701", "data" => "상품 송장번호 추가/수정에 실패했습니다.".$sql);
				}
			}else{
				$return_data = array("code" => "002702", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_shipping_info"){		// 0028 상품결제 배송내용변경
			$r_shipping_name   = ($_POST["shipping_name"] && $_POST["shipping_name"] != "")? $_POST["shipping_name"] : "";
			$r_shipping_cellphone = ($_POST["shipping_cellphone"] && $_POST["shipping_cellphone"] != "")? $_POST["shipping_cellphone"] : "";
			$r_shipping_zipcode   = ($_POST["shipping_zipcode"] && $_POST["shipping_zipcode"] != "")? $_POST["shipping_zipcode"] : "";
			$r_shipping_addr   = ($_POST["shipping_addr"] && $_POST["shipping_addr"] != "")? $_POST["shipping_addr"] : "";
			$r_shipping_addr2   = ($_POST["shipping_addr2"] && $_POST["shipping_addr2"] != "")? $_POST["shipping_addr2"] : "";
			$r_shipping_memo   = ($_POST["shipping_memo"] && $_POST["shipping_memo"] != "")? $_POST["shipping_memo"] : "";
			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$shipping_cellphone = (strpos($r_shipping_cellphone, "-") !== false)? str_replace("-", "", $r_shipping_cellphone) : $r_shipping_cellphone;
			
			if($r_order_num != ""){
				$sql = "
					UPDATE tb_item_payment_log SET
						`shipping_name` = '".addslashes($r_shipping_name)."', 
						`shipping_cellphone` = '".addslashes($shipping_cellphone)."', 
						`shipping_zipcode` = '".addslashes($r_shipping_zipcode)."', 
						`shipping_addr` = '".addslashes($r_shipping_addr)."', 
						`shipping_addr2` = '".addslashes($r_shipping_addr2)."', 
						`shipping_memo` = '".addslashes($r_shipping_memo)."', 
						`update_dt` = NOW()
					WHERE `order_num` = '".$r_order_num."'
				";
				$result = mysql_query($sql);

				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "002801", "data" => "상품 송장번호 추가/수정에 실패했습니다.".$sql);
				}
			}else{
				$return_data = array("code" => "002802", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_item_payment_cnt"){			// 0029 상품 총갯수 가져오기
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$where_qy = "";

			if($r_customer_id != ""){
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}

			$sql = "
				SELECT COUNT(*) AS cnt
				FROM tb_item_payment_log
				WHERE is_delete != '2'
				".$where_qy."
			";
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			$data = $row["cnt"];

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_item_payment_cancel_cnt"){		// 0030 상품 총취소갯수 가져오기
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$where_qy = "";

			if($r_customer_id != ""){
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}

			$sql = "
				SELECT COUNT(*) AS cnt
				FROM tb_item_payment_log
				WHERE is_delete != '2'
					AND pay_status in ('7', '8', '9')
				".$where_qy."
			";
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			$data = $row["cnt"];

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_item_product_no_chk"){			// 0031 상품번호 중복체크
			$r_product_no   = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
			$r_old_product_no = ($_POST["old_product_no"] && $_POST["old_product_no"] != "")? $_POST["old_product_no"] : "";

			$sql = "
				SELECT *
				FROM tb_item_list
				WHERE product_no = '".$r_product_no."'
					AND product_no != '".$r_old_product_no."'
			";
			$result = mysql_query($sql);
			$data = mysql_num_rows($result);

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_order_num"){					// 0032 상품결제 비회원 체크
			$r_order_name   = ($_POST["order_name"] && $_POST["order_name"] != "")? $_POST["order_name"] : "";
			$r_order_num	= ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";

			$sql = "
				SELECT *
				FROM tb_item_payment_log
				WHERE SUBSTRING_INDEX(guest_info,',',1) = '".$r_order_name."'
					AND order_num = '".$r_order_num."'
					AND is_delete != '2'
			";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			$data[] = $row;

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_update_bank_deposit"){			// 0033 상품결제 계좌이체 변경
			include "../include/Allimtalk.class.php";

			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_pay_status	   = ($_POST["pay_status"] && $_POST["pay_status"] != "")? $_POST["pay_status"] : "";
			$r_use_talk		   = ($_POST["use_talk"] && $_POST["use_talk"] != "")? $_POST["use_talk"] : "0";

			if($r_order_num != ""){
				$sql = "
					UPDATE tb_item_payment_log SET
						pay_status = '".$r_pay_status."',
						pay_dt = NOW(),
						update_dt = NOW()
					WHERE order_num = '".$r_order_num."'
				";
				$result = mysql_query($sql);

				if($result){
					if($r_use_talk == "1"){
						$sql = "
							SELECT *
							FROM tb_item_payment_log
							WHERE order_num = '".$r_order_num."'
						";
						$result = mysql_query($sql);
						$row = mysql_fetch_array($result);

						$talk = new Allimtalk();

						$talk->cellphone = $row['cellphone'];

						$talkCustomerName = substr($row['cellphone'], -4);
						$talkDate = date("y년 m월 d일 H시 i분", STRTOTIME($row['pay_dt']));
						$talkTotalPrice = number_format($row["total_price"])."원";
						$talkBtnLink = "https://gopet.kr/pet/chkodr/?no=".$r_order_num;
						$talkResult = $talk->sendOrderDeposit_new($talkCustomerName, $row["shipping_name"], $talkTotalPrice, $r_order_num, $talkDate, $row["product_name"], $talkBtnLink);
					}

					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "003301", "data" => "계좌이체 확인에 실패했습니다.".$sql);
				}
			}else{
				$return_data = array("code" => "003302", "data" => "중요 데이터가 누락됐습니다.".$sql);
			}
		}else if($r_mode == "set_update_item_return_n_cancel_step1"){ // 0034 상품 반품, 취소 - 상품선택
			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_action_type	   = ($_POST["action_type"] && $_POST["action_type"] != "")? $_POST["action_type"] : "";
			$r_chk_list		   = ($_POST["chk_list"] && $_POST["chk_list"] != "")? $_POST["chk_list"] : "";

			$_SESSION["RNC_ORDERNUM"] = $r_order_num;
			$_SESSION["RNC_CHKLIST"] = $r_chk_list;
			$_SESSION["RNC_ACTIONTYPE"] = $r_action_type;
			$_SESSION["RNC_STEP"] = "1";

			$return_data = array("code" => "000000", "data" => "OK");
		}else if($r_mode == "set_update_item_return_n_cancel_step2"){ // 0035 상품 반품, 취소 - 반품,취소 사유
			$r_reason_type	   = ($_POST["reason_type"] && $_POST["reason_type"] != "")? $_POST["reason_type"] : "";
			$r_reason_detail   = ($_POST["reason_detail"] && $_POST["reason_detail"] != "")? $_POST["reason_detail"] : "";

			$_SESSION["RNC_REASONTYPE"] = $r_reason_type;
			$_SESSION["RNC_REASONDETAIL"] = $r_reason_detail;
			$_SESSION["RNC_STEP"] = "2";

			$return_data = array("code" => "000000", "data" => "OK");
		}else if($r_mode == "set_update_item_return_n_cancel_step3"){ // 0036 상품 반품, 취소 - 환불방법
			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_return_pay_type = ($_POST["return_pay_type"] && $_POST["return_pay_type"] != "")? $_POST["return_pay_type"] : "2"; // 1 - 신용카드, 2 - 계좌이체
			$r_return_account  = ($_POST["return_account"] && $_POST["return_account"] != "")? $_POST["return_account"] : ""; // 계좌번호
			$r_return_bank  = ($_POST["return_bank"] && $_POST["return_bank"] != "")? $_POST["return_bank"] : ""; // 은행명
			$r_return_price  = ($_POST["return_price"] && $_POST["return_price"] != "")? $_POST["return_price"] : "0"; // 환불, 취소 금액
			$where_qy = "";

			if($_SESSION["RNC_ACTIONTYPE"] == "return"){ // 반품
				$where_qy .= ", pay_status = '7' ";
				$where_qy .= ", is_return = '2' ";
				$where_qy .= ", return_result = '".$_SESSION["RNC_REASONTYPE"]."|".$_SESSION["RNC_REASONDETAIL"]."' ";
				$where_qy .= ", return_result2 = '".$_SESSION["RNC_CHKLIST"]."|".$r_return_pay_type."|".$r_return_account."|".$r_return_bank."|".$r_return_price."' "; // 삭제아이템번호[]|타입|계좌번호|은행명|금액
				$where_qy .= ", return_dt = NOW() ";
			}else{
				$where_qy .= ", pay_status = '7' ";
				$where_qy .= ", is_cancel = '2' ";
				$where_qy .= ", cancel_result = '".$_SESSION["RNC_REASONTYPE"]."|".$_SESSION["RNC_REASONDETAIL"]."' ";
				$where_qy .= ", cancel_result2 = '".$_SESSION["RNC_CHKLIST"]."|".$r_return_pay_type."|".$r_return_account."|".$r_return_bank."|".$r_return_price."' "; // 삭제아이템번호[]|타입|계좌번호|은행명|금액
				$where_qy .= ", cancel_dt = NOW() ";
			}

			if($r_order_num == $_SESSION["RNC_ORDERNUM"]){
				$sql = "
					UPDATE tb_item_payment_log SET
						update_dt = NOW()
						".$where_qy."
					WHERE order_num = '".$_SESSION["RNC_ORDERNUM"]."'
				";
				$result = mysql_query($sql);

				if($result){
					$_SESSION["RNC_ORDERNUM"] = "";
					$_SESSION["RNC_CHKLIST"] = "";
					$_SESSION["RNC_ACTIONTYPE"] = "";
					$_SESSION["RNC_STEP"] = "";
					$_SESSION["RNC_REASONTYPE"] = "";
					$_SESSION["RNC_REASONDETAIL"] = "";

					$sql = "
						SELECT *
						FROM tb_item_payment_log
						WHERE order_num = '".$r_order_num."'
					";
					$result = mysql_query($sql);
					$row = mysql_fetch_assoc($result);
					if($row["point_price"] > 0){
						include "../include/Point.class.php";
						$point = new Point;
						$sql = "
							SELECT *
							FROM tb_point_history
							WHERE payment_log_seq = '".$row["ip_log_seq"]."'
								AND customer_id = '".$row["customer_id"]."'
								AND order_id = 'product_".$r_order_num."'
								AND type = 'SPEND'
						";
						$result2 = mysql_query($sql);
						$row2 = mysql_fetch_assoc($result2);
						if($row2["spending_accumulate_point"] >= 0 && $row2["spending_purchase_point"] >= 0){
							$spending_accumulate_point = intval($row2["spending_accumulate_point"]);
							$spending_purchase_point = intval($row2["spending_purchase_point"]);

							$result_point = $point->select_point($row["customer_id"]);
							if ($spending_accumulate_point > 0) {
								$point->add_accumulate_point($spending_accumulate_point, $row["ip_log_seq"], "cancel_" . $r_order_num);
							}
							if ($spending_purchase_point > 0) {
								$point->add_purchase_point($spending_purchase_point, $row["ip_log_seq"], $r_order_num);
							}
							$sql = "
								UPDATE tb_item_payment_log SET
									is_point_return = '2',
									update_dt = NOW()
								WHERE ip_log_seq = '".$row["ip_log_seq"]."'
									AND customer_id = '".$row["customer_id"]."'
									AND order_num = '".$r_order_num."'
							";
							$result3 = mysql_query($sql);
						}
					}

					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "003601", "data" => "반품, 취소 변경이 실패했습니다.".$sql);
				}
			}else{
				$return_data = array("code" => "003601", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_item_return_n_cancel_to_cancel"){ // 0037 상품 반품, 취소 - 반품,취소를 취소(철회)
			$_SESSION["RNC_ORDERNUM"] = "";
			$_SESSION["RNC_CHKLIST"] = "";
			$_SESSION["RNC_ACTIONTYPE"] = "";
			$_SESSION["RNC_STEP"] = "";
			$_SESSION["RNC_REASONTYPE"] = "";
			$_SESSION["RNC_REASONDETAIL"] = "";

			$return_data = array("code" => "000000", "data" => "OK");
		}else if($r_mode == "get_shop"){ // 0038 펫샵 정보 호출
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

			if($r_customer_id != ""){
				$Crypto = new Crypto();

				$sql = "
					SELECT *
					FROM tb_shop
					WHERE customer_id = '".$r_customer_id."'
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}

				$enc_customer_id = $Crypto->encode($r_customer_id, $access_key, $secret_key);
				$sql = "
					SELECT *
					FROM tb_request_artist
					WHERE customer_id = '".$enc_customer_id."'
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data["offline_shop_address"] = $Crypto->decode($row["offline_shop_address"], $access_key, $secret_key);
				}
				
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "003801", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_insert_item_payment_log_ready"){	// 0039 상품결제생성(INIpay 결제전)
			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_customer_name   = ($_POST["customer_name"] && $_POST["customer_name"] != "")? $_POST["customer_name"] : "";
			$r_customer_email  = ($_POST["customer_email"] && $_POST["customer_email"] != "")? $_POST["customer_email"] : "";
			$r_customer_email_suffix = ($_POST["customer_email_suffix"] && $_POST["customer_email_suffix"] != "")? $_POST["customer_email_suffix"] : "";
			$r_product_name	   = ($_POST["product_name"] && $_POST["product_name"] != "")? $_POST["product_name"] : "";
			$r_product_price   = ($_POST["product_price"] && $_POST["product_price"] != "")? $_POST["product_price"] : "0";
			$r_shipping_price  = ($_POST["shipping_price"] && $_POST["shipping_price"] != "")? $_POST["shipping_price"] : "0";
			$r_point_price	   = ($_POST["point_price"] && $_POST["point_price"] != "")? $_POST["point_price"] : "0";
			$r_total_price	   = ($_POST["total_price"] && $_POST["total_price"] != "")? $_POST["total_price"] : "0";
			$r_cellphone	   = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_shipping_name   = ($_POST["shipping_name"] && $_POST["shipping_name"] != "")? $_POST["shipping_name"] : "";
			$r_zipcode		   = ($_POST["zipcode"] && $_POST["zipcode"] != "")? $_POST["zipcode"] : "";
			$r_addr1		   = ($_POST["addr1"] && $_POST["addr1"] != "")? $_POST["addr1"] : "";
			$r_addr2		   = ($_POST["addr2"] && $_POST["addr2"] != "")? $_POST["addr2"] : "";
			$r_addr3		   = ($_POST["addr3"] && $_POST["addr3"] != "")? $_POST["addr3"] : "";
			$r_addr4		   = ($_POST["addr4"] && $_POST["addr4"] != "")? $_POST["addr4"] : "";
			$r_shipping_cellphone = ($_POST["shipping_cellphone"] && $_POST["shipping_cellphone"] != "")? $_POST["shipping_cellphone"] : "";
			$r_shipping_memo   = ($_POST["shipping_memo"] && $_POST["shipping_memo"] != "")? $_POST["shipping_memo"] : "";
			$r_pay_type		   = ($_POST["pay_type"] != "")? $_POST["pay_type"] : "1";
			$r_bank_name	   = ($_POST["bank_name"] && $_POST["bank_name"] != "")? $_POST["bank_name"] : "";

			$guest_info = trim($r_customer_name).",".trim($r_customer_email)."@".trim($r_customer_email_suffix);
			$shipping_zipcode = trim($r_zipcode);
			$shipping_addr = trim($r_addr1)." ".trim($r_addr3);
			$shipping_addr2 = trim($r_addr4);
			$expire_dt = "";
			$bank_name = "";
			$add_column1 = "";
			$add_column1_val = "";
			$reg_dt = DATE("Y-m-d H:i:s"); // 입력일시
			$pay_type_arr = array("", "card", "bank"); // card만 사용예정
			$cellphone = (strpos($r_cellphone, "-") !== false)? str_replace("-", "", $r_cellphone) : $r_cellphone;
			$shipping_cellphone = (strpos($r_shipping_cellphone, "-") !== false)? str_replace("-", "", $r_shipping_cellphone) : $r_shipping_cellphone;

			if($r_pay_type == "1"){ // 신용카드
				$add_column1 = ", `pay_status` ";
				$add_column1_val = ", '1' "; // 진행중, 
			}else if($r_pay_type == "2"){ // 계좌이체
				$expire_dt = date("Y-m-d H:i:s", getBankDDay(intval(date('H'))));
				$bank_name = $r_bank_name;
				$add_column1 = ", `bank_name`, `expire_dt`, `pay_status` ";
				$add_column1_val = ", '".$bank_name."', '".$expire_dt."', '2' "; // 입금대기중
			}

			$sql = "
				SELECT *
				FROM tb_item_cart
				WHERE order_num = '".$r_order_num."'
			";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			$pay_data = $row["cart_data"];

			$sql = "
				INSERT INTO tb_item_payment_log (
					`order_num`, `customer_id`, `guest_info`, `product_name`, `product_price`, 
					`point_price`, `shipping_price`, `total_price`, `cellphone`, `shipping_name`, 
					`shipping_zipcode`, `shipping_addr`, `shipping_addr2`, `shipping_cellphone`, `shipping_memo`, `pay_type`, `pay_data`, 
					`reg_dt` ".$add_column1."
				) VALUES (
					'".$r_order_num."', '".$r_customer_id."', '".$guest_info."', '".$r_product_name."', '".$r_product_price."', 
					'".$r_point_price."', '".$r_shipping_price."', '".$r_total_price."', '".$cellphone."', '".addslashes($r_shipping_name)."', 
					'".addslashes($shipping_zipcode)."', '".addslashes($shipping_addr)."', '".addslashes($shipping_addr2)."', '".$shipping_cellphone."', '".addslashes($r_shipping_memo)."', 
					'".$r_pay_type."', '".$pay_data."', '".$reg_dt."' ".$add_column1_val."
				)				
			";
			$result = mysql_query($sql);

			if($result){
				$return_data = array("code" => "000000", "data" => array("price" => $r_total_price, "product_name" => $r_product_name, "pay_type" => $pay_type_arr[$r_pay_type]));
			}else{
				$return_data = array("code" => "003901", "data" => "결제 데이터 생성에 실패했습니다.");
			}
		}else if($r_mode == "get_item_payment_log_chk"){		// 0040 상품결제 전 내역 확인
			// add search keyword
			$r_order_num	   = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";

			$sql = "
				SELECT *
				FROM tb_item_payment_log
				WHERE is_delete != '2'
					AND order_num = '".$r_order_num."'
					AND pay_status = '1'
			";
			$result = mysql_query($sql);
			$item_payment_cnt = mysql_num_rows($result);
			if($item_payment_cnt > 0){
				while($row = mysql_fetch_array($result)){
					$data[] = $row;
				}
			}else{
				$data = $item_payment_cnt;
			}
			
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_update_item_payment_log_price_zero"){// 0041 PG 없이 바로 거래 완료 처리
			include "../include/Point.class.php";
			include "../include/Allimtalk.class.php"; // 알림톡 Class 호출

			$r_order_num = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

			$sql = "
				SELECT *
				FROM tb_item_payment_log
				WHERE order_num = '".$r_order_num."'
					AND customer_id = '".$r_customer_id."'				
			";
			$result = mysql_query($sql);
			$payment_cnt = mysql_num_rows($result);
			$row = mysql_fetch_assoc($result);

			if($payment_cnt == 1){
				// 예약건 삭제
				$delete_id = ($row["customer_id"] != "")? $row["customer_id"] : $row["cellphone"];
				$sql = "
					UPDATE tb_item_cart SET
						`is_delete` = '2', 
						`delete_txt` = '".$delete_id."|결제 완료로 인해 자동 삭제', 
						`delete_dt` = NOW()
					WHERE is_delete = '1'
						AND order_num = '".$r_order_num."'
				";
				$result = mysql_query($sql);

				// 거래내역 업데이트
				if($row["pay_type"] == "1"){ // 신용카드
					$sql = "
						UPDATE tb_item_payment_log SET
							pg_log = 'direct_payment',
							receipt_id = '".$r_order_num."',
							pay_status = '3',
							pay_dt = NOW(),
							update_dt = NOW()
						WHERE order_num = '".$r_order_num."'
					";
					$result = mysql_query($sql);
				}else if($row["pay_type"] == "2"){ // 계좌이체
					$result = true; // 계좌이체는 추가 변경 데이터가 없으므로 true 처리
				}

				if($result){
					if($row["customer_id"] != "" && $row["point_price"] > 0){ // 포인트 결제 사용시 포인트 감소
						$point = new Point;
						$result = $point->select_point($row["customer_id"]);
						$point->spend_point($row["point_price"], $row["ip_log_seq"], "product_".$row["order_num"]);
					}

					// 알림톡 발송 / PUSH 발송
					if($row["pay_type"] == "1"){ // 신용카드
						$talk = new Allimtalk();

						$talk->cellphone = $row["cellphone"];

						$talkCustomerName = substr($row["cellphone"], -4);
						$talkDate = date("y년 m월 d일 H시 i분", STRTOTIME(DATE("Y-m-d H:i:s")));
						$talkBtnLink = "https://gopet.kr/pet/chkodr/?no=".$row["order_num"];
						$talkResult = $talk->sendOrderReceipt_new($talkCustomerName, $row["order_num"], $talkDate, $row["product_name"], $talkBtnLink);
					}else if($row["pay_type"] == "2"){ // 계좌이체
						$talk = new Allimtalk();

						$talk->cellphone = $row["cellphone"];

						$talkCustomerName = substr($row["cellphone"], -4);
						$talkDate = date("y년 m월 d일 H시 i분", STRTOTIME(DATE("Y-m-d H:i:s")));
						$talkExpireDate = date("y년 m월 d일 H시 i분", STRTOTIME($row["expire_dt"]));
						$talkTotalPrice = number_format($row["total_price"])."원";
						$talkBtnLink = "https://gopet.kr/pet/chkodr/?no=".$row["order_num"];
						$talkResult = $talk->sendOrderAccount_new($talkCustomerName, $row["order_num"], $talkDate, $row["product_name"], $talkExpireDate, $talkTotalPrice, $row["shipping_name"], $talkBtnLink);
					}

					// 관리자 푸시 발송
					$pushPath = "https://www.gopet.kr/pet/admin/item_payment_log_detail.php?no=".$row["order_num"];
					//$pushImage = "https://www.gopet.kr/pet/images/logo_login.jpg";
					$pushImage = "";
					$pushPayType = ($row["pay_type"] == "1")? "카드" : "계좌이체";
					$admin_message = substr($row["cellphone"], -4) . "(".explode(",", $row["guest_info"])[1].")님이 [".$row["product_name"]."]을 구매(".$pushPayType."). 상품결제 관리를 확인하세요";
					a_push("pickmon@pickmon.com", "반짝_상품구매알림", $admin_message, $pushPath, $pushImage);

					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					// 거래 업데이트 실패 - 변경 실패
					$return_data = array("code" => "004101", "data" => "거래 변경에 실패했습니다.".$sql);
				}
			}else if($payment_cnt <= 0){
				// 데이터가 존재하지 않음 - 변경할 데이터가 없음
				$return_data = array("code" => "004102", "data" => "거래가 정상적으로 이루어 지지 않았습니다. 다시 처음부터 시도해 주십시요.");
			}else{
				// 중복 거래건이 있음 - 변경할 경우 데이터 손실 가능성 있음
				$return_data = array("code" => "004103", "data" => "거래가 정상적으로 이루어 지지 않았습니다. 다시 처음부터 시도해 주십시요.");
			}
		}else if($r_mode == "get_item_event"){
			$r_ie_seq = ($_POST["ie_seq"] && $_POST["ie_seq"] != "")? $_POST["ie_seq"] : "";
			$r_il_seq = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
			$where_qy = "";

			if($r_ie_seq != ""){
				$where_qy .= " AND ie_seq = '".$r_ie_seq."' ";
			}
			if($r_il_seq != ""){
				$where_qy .= " AND il_seq = '".$r_il_seq."' ";
			}

			$sql = "
				SELECT *
				FROM tb_item_event
				WHERE is_delete = '2'
					".$where_qy."
				ORDER BY reg_dt DESC
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_insert_item_event"){
			$r_il_seq	   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
			$r_title	   = ($_POST["title"] && $_POST["title"] != "")? $_POST["title"] : "";
			$r_plus_cnt    = ($_POST["plus_cnt"] && $_POST["plus_cnt"] != "")? $_POST["plus_cnt"] : "0";
			$r_total_cnt   = ($_POST["total_cnt"] && $_POST["total_cnt"] != "")? $_POST["total_cnt"] : "0";
			$r_start_dt    = ($_POST["start_dt"] && $_POST["start_dt"] != "")? $_POST["start_dt"] : "";
			$r_end_dt	   = ($_POST["end_dt"] && $_POST["end_dt"] != "")? $_POST["end_dt"] : "";

			if($r_il_seq != "" && $r_total_cnt > 0){
				$sql = "
					INSERT INTO tb_item_event (
						`il_seq`, `title`, `plus_cnt`, `total_cnt`, `start_dt`, 
						`end_dt`
					) VALUES (
						'".$r_il_seq."', '".$r_title."', '".$r_plus_cnt."', '".$r_total_cnt."', '".$r_start_dt."', 
						'".$r_end_dt."'
					)
				";
				$result = mysql_query($sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "004202", "data" => "이벤트 등록에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "004201", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_item_event"){
			$r_ie_seq	   = ($_POST["ie_seq"] && $_POST["ie_seq"] != "")? $_POST["ie_seq"] : "";
			$r_il_seq	   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
			$r_title	   = ($_POST["title"] && $_POST["title"] != "")? $_POST["title"] : "";
			$r_plus_cnt    = ($_POST["plus_cnt"] != "")? $_POST["plus_cnt"] : "";
			$r_total_cnt   = ($_POST["total_cnt"] != "")? $_POST["total_cnt"] : "";
			$r_start_dt    = ($_POST["start_dt"] && $_POST["start_dt"] != "")? $_POST["start_dt"] : "";
			$r_end_dt	   = ($_POST["end_dt"] && $_POST["end_dt"] != "")? $_POST["end_dt"] : "";
			$r_is_use	   = ($_POST["is_use"] && $_POST["is_use"] != "")? $_POST["is_use"] : "";
			$update_qy = "";

			if($r_il_seq != ""){
				$update_qy .= " `il_seq` = '".$r_il_seq."', ";
			}
			if($r_title != ""){
				$update_qy .= " `title` = '".$r_title."', ";
			}
			if($r_plus_cnt != ""){
				$update_qy .= " `plus_cnt` = '".$r_plus_cnt."', ";
			}
			if($r_total_cnt != ""){
				$update_qy .= " `total_cnt` = '".$r_total_cnt."', ";
			}
			if($r_start_dt != ""){
				$update_qy .= " `start_dt` = '".$r_start_dt."', ";
			}
			if($r_end_dt != ""){
				$update_qy .= " `end_dt` = '".$r_end_dt."', ";
			}
			if($r_is_use != ""){
				$update_qy .= " `is_use` = '".$r_is_use."', ";
			}

			if($r_ie_seq != ""){
				$sql = "
					UPDATE tb_item_event SET
						".$update_qy."
						`update_dt` = NOW()
					WHERE is_delete = '2'
						AND ie_seq = '".$r_ie_seq."'
				";
				$result = mysql_query($sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "004302", "data" => "이벤트 수정에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "004301", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_delete_item_event"){
			$r_ie_seq	   = ($_POST["ie_seq"] && $_POST["ie_seq"] != "")? $_POST["ie_seq"] : "";
			$r_delete_id   = ($_POST["delete_id"] && $_POST["delete_id"] != "")? $_POST["delete_id"] : "";
			$r_delete_msg  = ($_POST["delete_msg"] && $_POST["delete_msg"] != "")? $_POST["delete_msg"] : "";

			if($r_ie_seq != ""){
				$sql = "
					UPDATE tb_item_event SET
						`is_delete` = '1',
						`delete_msg` = '".$r_delete_id."|".$r_delete_msg."',
						`delete_dt` = NOW()
					WHERE is_delete = '2'
						AND ie_seq = '".$r_ie_seq."'
				";
				$result = mysql_query($sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "004402", "data" => "이벤트 삭제에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "004401", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_now_cnt"){
			$r_il_seq	   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
			$r_start_dt    = ($_POST["start_dt"] && $_POST["start_dt"] != "")? $_POST["start_dt"] : "";
			$r_end_dt	   = ($_POST["end_dt"] && $_POST["end_dt"] != "")? $_POST["end_dt"] : "";
			$where_qy = "";

			if($r_il_seq != ""){
				$where_qy .= " AND il_seq = '".$r_il_seq."' ";
			}

			if($r_start_dt != "" && $r_end_dt != ""){
				$sql = "
					SELECT COUNT(*) AS cnt
					FROM tb_item_payment_log 
					WHERE is_delete = '1'
						AND pay_status IN (3, 4, 5, 6)
						AND pay_dt BETWEEN '".$r_start_dt."' AND '".$r_end_dt."'
						".$where_qy."
				";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				$data = $row["cnt"];

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "004501", "data" => "날짜 범위가 올바르지 않습니다.");
			}
		}else if($r_mode == "get_item_review"){
			$r_ir_seq = ($_POST["ir_seq"] && $_POST["ir_seq"] != "")? $_POST["ir_seq"] : "";
			$r_order_num = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_product_no = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$where_qy = "";

			if($r_ir_seq != ""){
				$where_qy .= " AND ir.ir_seq = '".$r_ir_seq."' ";
			}
			if($r_order_num != ""){
				$where_qy .= " AND ir.order_num = '".$r_order_num."' ";
			}
			if($r_product_no != ""){
				$where_qy .= " AND ir.product_no = '".$r_product_no."' ";
			}
			if($r_customer_id != ""){
				$where_qy .= " AND ir.customer_id = '".$r_customer_id."' ";
			}

			if($where_qy != ""){
				$sql = "
					SELECT ir.*, ipl.product_name, ipl.pay_data, cu.nickname, cu.photo
					FROM tb_item_review AS ir
						LEFT OUTER JOIN tb_customer AS cu ON ir.customer_id = cu.id
						LEFT OUTER JOIN tb_item_payment_log AS ipl ON ir.order_num = ipl.order_num
					WHERE ir.is_delete = '2'
						".$where_qy."
					ORDER BY ir.reg_dt DESC
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "004601", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_insert_item_review"){
			$r_product_no = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
			$r_order_num = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_review = ($_POST["review"] && $_POST["review"] != "")? $_POST["review"] : "";
			$r_review_image = ($_POST["review_image"] && $_POST["review_image"] != "")? $_POST["review_image"] : "";
			$r_rating = ($_POST["rating"] && $_POST["rating"] != "")? $_POST["rating"] : "";
			$r_is_admin = ($_POST["is_admin"] && $_POST["is_admin"] != "")? $_POST["is_admin"] : "";
			$r_name = ($_POST["name"] && $_POST["name"] != "")? $_POST["name"] : "";
			$r_product_option_txt = ($_POST["product_option_txt"] && $_POST["product_option_txt"] != "")? $_POST["product_option_txt"] : "";
			$is_admin = "";
			$is_admin2 = "";

			if($r_is_admin == "1"){
				$is_admin = ", `is_admin`, `name`, `product_option_txt` ";
				$is_admin2 = ", '".$r_is_admin."', '".$r_name."', '".$r_product_option_txt."' ";
			}

			$sql = "
				INSERT INTO tb_item_review (
					`product_no`, `order_num`, `customer_id`, `review`, `review_image`,
					`rating` ".$is_admin."
				) VALUES (
					'".$r_product_no."', '".$r_order_num."', '".$r_customer_id."', '".trim(addslashes($r_review))."', '".$r_review_image."',
					'".$r_rating."' ".$is_admin2."
				)
			";
			$result = mysql_query($sql);
			if($result){
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "004701", "data" => "리뷰 등록에 실패했습니다.".$sql);
			}
		}else if($r_mode == "set_update_item_review"){
			$r_ir_seq = ($_POST["ir_seq"] && $_POST["ir_seq"] != "")? $_POST["ir_seq"] : "";
			$r_product_no = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
			$r_order_num = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_review = ($_POST["review"] && $_POST["review"] != "")? $_POST["review"] : "";
			$r_review_image = ($_POST["review_image"] && $_POST["review_image"] != "")? $_POST["review_image"] : "";
			$r_rating = ($_POST["rating"] && $_POST["rating"] != "")? $_POST["rating"] : "";
			$r_is_blind = ($_POST["is_blind"] != "")? $_POST["is_blind"] : "";
			$r_is_reply = ($_POST["is_reply"] != "")? $_POST["is_reply"] : "";
			$r_reply = ($_POST["reply"] && $_POST["reply"] != "")? $_POST["reply"] : "";
			$r_is_admin = ($_POST["is_admin"] && $_POST["is_admin"] != "")? $_POST["is_admin"] : "";
			$r_name = ($_POST["name"] && $_POST["name"] != "")? $_POST["name"] : "";
			$r_product_option_txt = ($_POST["product_option_txt"] && $_POST["product_option_txt"] != "")? $_POST["product_option_txt"] : "";
			$update_qy = "";
			$where_qy = "";

			if($r_ir_seq != ""){
				$where_qy .= " AND ir_seq = '".$r_ir_seq."' ";
			}
			if($r_product_no != ""){
				$where_qy .= " AND product_no = '".$r_product_no."' ";
			}
			if($r_order_num != ""){
				$where_qy .= " AND order_num = '".$r_order_num."' ";
			}
			if($r_customer_id != ""){
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}

			if($r_review != ""){
				$update_qy .= " review = '".trim(addslashes($r_review))."', ";
			}
			if(isset($_POST["review_image"])){
				$update_qy .= " review_image = '".$r_review_image."', ";
			}
			if($r_rating != ""){
				$update_qy .= " rating = '".$r_rating."', ";
			}
			if($r_is_blind != ""){
				$update_qy .= " is_blind = '".$r_is_blind."', ";
				$update_qy .= ($r_is_blind == "1")? " blind_dt = NOW(), " : " blind_dt = NULL, ";
			}
			if($r_is_reply != ""){
				$update_qy .= " is_reply = '".$r_is_reply."', ";
				$update_qy .= ($r_is_reply == "1")? " reply_dt = NOW(), " : " reply_dt = NULL, ";
			}
			if($r_reply != ""){
				$update_qy .= " reply = '".trim(addslashes($r_reply))."', ";
				$update_qy .= " reply_dt = NOW(), ";
			}
			if($r_is_admin != "" && $r_is_admin == "1"){
				$update_qy .= " name = '".$r_name."', ";
				$update_qy .= " product_option_txt = '".$r_product_option_txt."', ";
			}

			if($where_qy != "" && $update_qy != ""){
				$sql = "
					UPDATE tb_item_review SET
						".$update_qy."
						update_dt = NOW()
					WHERE is_delete = '2'
						".$where_qy."
				";
				$result = mysql_query($sql);
				if($result){
					$return_data = array("code" => "000000", "data" => $data);
				}else{
					$return_data = array("code" => "004801", "data" => "리뷰 수정에 실패했습니다.".$sql);
				}
			}else{
				$return_data = array("code" => "004802", "data" => "중요 데이터가 누락되었습니다.".$sql);
			}
		}else if($r_mode == "set_delete_item_review"){
			$r_ir_seq = ($_POST["ir_seq"] && $_POST["ir_seq"] != "")? $_POST["ir_seq"] : "";
			$r_delete_id = ($_POST["delete_id"] && $_POST["delete_id"] != "")? $_POST["delete_id"] : "";
			$r_delete_msg = ($_POST["delete_msg"] && $_POST["delete_msg"] != "")? $_POST["delete_msg"] : "";

			if($r_ir_seq != ""){
				$sql = "
					UPDATE tb_item_review SET
						is_delete = '1',
						delete_msg = '".$r_delete_id."|".$r_delete_msg."',
						delete_dt = NOW()
					WHERE is_delete = '2'
						AND ir_seq = '".$r_ir_seq."'
				";
				$result = mysql_query($sql);
				if($result){
					$return_data = array("code" => "000000", "data" => $data);
				}else{
					$return_data = array("code" => "004901", "data" => "리뷰 삭제에 실패했습니다.".$sql);
				}
			}else{
				$return_data = array("code" => "004902", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_item_cart"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_ic_seq = ($_POST["ic_seq"] && $_POST["ic_seq"] != "")? $_POST["ic_seq"] : "";
			$where_qy = "";

			if($r_ic_seq != ""){
				$where_qy .= " AND ic_seq = '".$r_ic_seq."' ";
			}
			if($sessionid != ""){
				$where_qy .= " AND session_id = '".$sessionid."' ";
			}
			if($r_customer_id != ""){
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}

			if($where_qy != ""){
				$sql = "
					SELECT *
					FROM tb_item_cart
					WHERE is_delete = '1'
						".$where_qy."
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "005001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_cart"){ // 0051 상품 카트 업데이트(이게 왜 없었지.. ㅠㅠ)
			$r_ic_seq = ($_POST["ic_seq"] && $_POST["ic_seq"] != "")? $_POST["ic_seq"] : "";
			$r_cart_data = ($_POST["cart_data"] && $_POST["cart_data"] != "")? $_POST["cart_data"] : "";
			$r_cart_price = ($_POST["cart_price"] && $_POST["cart_price"] != "")? $_POST["cart_price"] : "";
			$r_order_num = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$where_qy = "";
			$update_qy = "";

			if($r_ic_seq != ""){
				$where_qy .= " AND ic_seq = '".$r_ic_seq."' ";
			}
			if($sessionid != ""){
				$where_qy .= " AND session_id = '".$sessionid."' ";
			}

			if($r_cart_data != ""){
				$update_qy .= " cart_data = '".$r_cart_data."', ";
			}
			if($r_cart_price != ""){
				$update_qy .= " cart_price = '".$r_cart_price."', ";
			}
			if($r_order_num != ""){
				$update_qy .= " order_num = '".$r_order_num."', ";
			}

			if($where_qy != "" && $update_qy != ""){
				$sql = "
					UPDATE tb_item_cart SET
						".$update_qy."
						update_dt = NOW()
					WHERE is_delete = '1'
						".$where_qy."
						
				";
				$result = mysql_query($sql);
				if($result){
					$return_data = array("code" => "000000", "data" => $data);
				}else{
					$return_data = array("code" => "005102", "data" => "상품 카트 수정에 실패했습니다.".$sql);
				}
			}else{
				$return_data = array("code" => "005101", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_item_payment_log_jbook"){ // 정글북 결제내역 불러오기
			$r_order_num = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";
			$r_jbOrdNo = ($_POST["jbOrdNo"] && $_POST["jbOrdNo"] != "")? $_POST["jbOrdNo"] : "";

			if($r_order_num != "" && $r_jbOrdNo != ""){
				$sql = "
					SELECT *
					FROM tb_item_payment_log_jbook
					WHERE is_delete = '2'
						AND order_num = '".$r_order_num."'
						AND ordNo = '".$r_jbOrdNo."'
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "005201", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);

	function INI_PartialRefund($tid, $msg, $price, $confirmPrice, $curl_error_arr){
		// $P_REQ_URL = "https://stginiapi.inicis.com/api/v1/refund"; // test
		$P_REQ_URL = "https://iniapi.inicis.com/api/v1/refund"; // live
		// IV = "r9o5yPICNELvqY=="; // live

		// $KEY = "ItEQKi3rY7uvDS8l"; // test
		$KEY = "dTHUN5hZi8Fx9KTO"; // live

		$type = "PartialRefund";
		$paymethod = "Card";
		$timestamp = DATE("YmdHis");
		$clientIp = "175.126.123.165";
		//$mid = "INIpayTest"; // test
		$mid = "banjjak001"; // live

		$hashData = hash('sha512', $KEY.$type.$paymethod.$timestamp.$clientIp.$mid.$tid.$price.$confirmPrice);
		//echo $KEY.$type.$paymethod.$timestamp.$clientIp.$mid.$tid.$price.$confirmPrice."<br/>";
		//echo $hashData."<br/>";

		$post_data  = "type=".$type;
		$post_data .= "&paymethod=".$paymethod;
		$post_data .= "&timestamp=".$timestamp;
		$post_data .= "&clientIp=".$clientIp;
		$post_data .= "&mid=".$mid;
		$post_data .= "&tid=".$tid;
		$post_data .= "&msg=".$msg;
		$post_data .= "&price=".$price;
		$post_data .= "&confirmPrice=".$confirmPrice;
		$post_data .= "&hashData=".$hashData;
		//echo $post_data."<br/>";
		$headers = array('Content-Type: application/x-www-form-urlencoded;charset=utf-8');

		$ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

		curl_setopt($ch, CURLOPT_URL, $P_REQ_URL);			// URL 지정하기
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		// 결과를 노출(0-print, 1-변수저장)
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// https ssl 인증서 확인 하지 않도록 함
		curl_setopt($ch, CURLOPT_SSLVERSION,3);				// 주소가 https가 아니라면 지울것
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);		// header 정보 전달
        
		curl_setopt($ch, CURLOPT_POST, 1);					// 0이 default 값이며 POST 통신을 위해 1로 설정해야 함
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);	// POST로 보낼 데이터 지정하기

		$response = curl_exec($ch);
	
		//echo '>> result << <br/>';
		$tmp_arr = json_decode($response, true); //결과값 확인하기
		//echo '<br/><br/>';
		//print_r(curl_getinfo($ch));//마지막 http 전송 정보 출력
		if(curl_errno($ch)){ //마지막 에러 번호 출력 
			$tmp_err = "curl_error: ".curl_error($ch)."(".curl_errno($ch).")";//현재 세션의 마지막 에러 출력
		}
		curl_close($ch);

		if($tmp_err == ""){
			if($tmp_arr["resultCode"] == "00"){ // 정상 취소
				return array("code" => "000000", "data" => $tmp_arr);
			}else{
				return array("code" => "002401", "data" => $tmp_arr["resultMsg"]." ( ".$tmp_arr["resultCode"]." ) ");
				//print_r($tmp_arr);
			}
		}else{
			return array("code" => "002402", "data" => $tmp_err);
		}
	}
?>