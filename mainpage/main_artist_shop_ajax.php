<?php
	include "../include/configure.php";
	include "../include/db_connection.php";
	include "../include/Crypto.class.php";

	if (!$artist_id) {
		$artist_id = $_REQUEST["artist_id"];
	}

	include "main_artist_shop.php";	
?>
