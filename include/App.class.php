<?php
class App {
	public $is_android = 0;

	function is_app () {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->is_android = 0;
		if (user_agent) {
			$token_index = strpos($user_agent, "APP_GOPET_PARTNER_AND");
		        if ($token_index > 0) {
        		        $this->is_android = 1;
	        	}
		}
		return $this->is_android;
	}
}
?>
