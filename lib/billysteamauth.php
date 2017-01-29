<?php
	@session_start();
	
	class BillySteamAuth {
		public function __construct($sessionname = "steamid") {
			if (isset($_SESSION[$sessionname])) {
				$this -> SteamID = $_SESSION[$sessionname];
			} else {
				require("openid.php");
				$this -> openid = new LightOpenID($_SERVER["HTTP_HOST"]);
				$this -> openid -> identity = "https://steamcommunity.com/openid";
				
				if ($this -> openid -> mode) {
					if ($this -> openid -> validate()) {
						$this -> SteamID = basename($this -> openid -> identity);
						$_SESSION[$sessionname] = $this -> SteamID;
					}
				}
			}
		}
		
		public function LoginURL() {
			return $this -> openid -> authUrl();
		}
		
		public function StripOpenID($get = null) {
			if (!$get) {
				$get = $_GET;
			}
			$OpenID = [
				
				"openid_ns",
				"openid_mode",
				"openid_op_endpoint",
				"openid_claimed_id",
				"openid_identity",
				"openid_return_to",
				"openid_response_nonce",
				"openid_assoc_handle",
				"openid_signed",
				"openid_sig",
				
			];
			$builduri = "";
			foreach($get as $key => $value) {
				if (!in_array($key,$OpenID)) {
					if ($builduri == "") {
						$builduri = "?";
					} else {
						$builduri .= "&";
					}
					$builduri .= $key . "=" . $value;
				}
			}
			return $builduri;
		}
	}
?>