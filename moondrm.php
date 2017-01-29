<?php
	@session_start();

	require("lib/billysteamauth.php");

	class MoonDRM {

		public $BillySteamAuth;
		public $Database;
		public $DRM;

		public $Config = [

			"CookieName" => "MoonDRM_LoginCookie",

			"API-User-Agent" => "MoonDRM (moondrm.com) by Billy (76561198040894045)",

			"User-Agent" => "Valve/Steam HTTP Client 1.0 (4000)",

			"MySQL" => [

				"Host"     => "localhost",
				"Username" => "drm",
				"Password" => "removed",
				"Database" => "drm",

			],

			"SteamAPIKey" => "removed",

			"RootUsers" => [

				"76561198040894045",

			],

			"InstallationDirectory" => "/var/www/moondrm",

		];

		public $UserSettings = [

			"General" => [

				"Date/Time Format" => "jS M Y g:ia e",

			],

			"ScriptFodder" => [

				"API_Key" => "",

			],

		];

		public function CheckIfDeveloper() {
			$q = $this -> query("SELECT NULL FROM `developers` WHERE `steamid64`=" . $this->e($this->SteamID64));
			if ($q -> num_rows == 0) {
				header("LOCATION: /buy");
			}
		}

		public function CheckUserAgent() {
			if (!isset($_SERVER["HTTP_USER_AGENT"])) return false;
			return $_SERVER["HTTP_USER_AGENT"] == $this -> Config["User-Agent"];
		}

		public function config($config) {
			return $this -> Config[$config];
		}

		public function query($q) {
			$qu = $this -> Database -> Database -> query($q);

			if (!$qu) {
				throw new Exception("MySQL Error: " . $this -> Database -> Database -> error);
			}

			return $qu;
		}

		public function e($str) {
			if (gettype($str) != "string") {
				file_put_contents("/var/www/moondrm/output.txt",serialize($str));
			}
			return "'" . $this -> Database -> Database -> real_escape_string($str) . "'";
		}

		public function __construct($needslogin = true) {
			$this -> Database = new stdClass();
			$this -> Database -> Database = new mysqli($this -> Config["MySQL"]["Host"], $this -> Config["MySQL"]["Username"], $this -> Config["MySQL"]["Password"], $this -> Config["MySQL"]["Database"]);
			$this -> Database -> Database -> set_charset("utf8");

			if (!isset($_SESSION[$this -> Config["CookieName"]])) {

				$this -> API_Key = null;
				$this -> SteamID64 = null;

				if ($needslogin) {

					if ($_SERVER["REQUEST_URI"] != "/")
						header("LOCATION: /login/?redirect=" . urlencode($_SERVER["REQUEST_URI"]));
					else
						header("LOCATION: /login/");

				}

			} else {

				$this -> SteamID64 = $_SESSION[$this -> Config["CookieName"]];

				$settings = $this -> query("SELECT `settings` FROM `developers` WHERE `steamid64`=" . $this->e($this -> SteamID64)) -> fetch_array();
				$this -> Settings = json_decode($settings["settings"],true);
				$settings = $this -> Settings;

				if (isset($settings["ScriptFodder"]))
					$this -> API_Key = $settings["ScriptFodder"]["API_Key"];

			}

			$this -> DRM = new stdClass();
		}

		public function GetSteamTable($steamid64) {

			$q = $this -> query("SELECT `steamtable` FROM `users` WHERE `steamid64`=" . $this->e($steamid64) . "");
			if (!$q) {
				return [];
			}
			if ($q -> num_rows == 0) {

				$steamtable = json_decode(file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $this->Config["SteamAPIKey"] . "&steamids=" . $steamid64),true);
				if (!$steamtable) {
					return [];
				}

				$steamtable = $steamtable["response"]["players"][0];

				$this -> query("INSERT INTO `users` (`steamid64`,`steamtable`) VALUES(" . $this->e($steamid64) . "," . $this->e(json_encode($steamtable)) . ")");

				return $steamtable;

			}
			$q = $q -> fetch_array();

			return json_decode($q["steamtable"],true);

		}

		public function Logout() {
			unset($_SESSION[$this -> Config["CookieName"]]);
		}

		public function GetDeveloper($column = null,$value = null) {

			if ($column == null)
				$column = "steamid64";
			if ($value == null)
				$value = $_SESSION[$this -> Config["CookieName"]];

			$q = $this -> query("SELECT * FROM `developers` WHERE `" . $column . "`=" . $this->e($value));
			if ($q -> num_rows == 0) {
				return false;
			}

			$q = $q -> fetch_array();
			$q["settings"] = json_decode($q["settings"],true);

			return $q;

		}

		public function ThrowError($error) {

			die('--#MoonDRM-Error#
MsgC(Color(255,0,0),"[MoonDRM] ",Color(255,255,255),"' . str_replace('"','\\"',$error) . '")');

		}

		public function EncodeLua($lua,$hash) {

			// encryption/decryption stuff removed because it's also kinda used in my new DRM

			return "--#MoonDRM#\n" . $lua);

		}

		public function ScriptFodderAPI($url,$post = false) {
			return json_decode(file_get_contents("https://scriptfodder.com/api/" . $url,false,stream_context_create(["http"=>[

				"method" => ($post ? "POST" : "GET"),
				"header" => "User-Agent: " . $this -> Config["User-Agent"],

			]])),true);
		}

		public function GenerateHash($steamid64,$script_id,$number = 1) {
			return hash("sha256","hashkey generation removed too");
		}

		public function GetScriptImage($script_id) {
			$q = $this -> query("SELECT `image` FROM `scripts` WHERE `script_id`=" . $this->e($script_id)) -> fetch_array();
			if (!file_exists($this -> Config["InstallationDirectory"] . "/assets/img/scripts/$script_id.png")) {
				file_put_contents($this -> Config["InstallationDirectory"] . "/assets/img/scripts/$script_id.png",file_get_contents($q["image"]));
			}
			return "/assets/img/scripts/$script_id.png";
		}

		public function Sync() {

			$q = $this -> query("SELECT * FROM `developers`");

			while($row = $q -> fetch_assoc()) {

				$apikey = json_decode($row["settings"],true);
				$apikey = $apikey["ScriptFodder"]["API_Key"];

				if (empty($apikey)) {continue;}

				$q2 = $this -> query("SELECT * FROM `scripts` WHERE `enabled`=1 AND `developer`=" . $this->e($row["steamid64"]));

				while($row2 = $q2 -> fetch_assoc()) {

					$r = $this -> ScriptFodderAPI("scripts/purchases/" . $row2["script_id"] . "?api_key=$apikey");
					$r = $r["purchases"];
					array_push($r,[

						"user_id" => $row["steamid64"],
						"purchase_revoked" => 0,

					]);

					foreach($r as $user) {

						$this -> query("INSERT IGNORE INTO `hashes` (`script_id`,`developer`,`steamid64`,`revoked`,`hash`) VALUES(" . $this->e($row2["script_id"]) . "," . $this->e($row["steamid64"]) . "," . $this->e($user["user_id"]) . "," . $this->e($user["purchase_revoked"]) . "," . $this->e($this->GenerateHash($user["user_id"],$row2["script_id"],1)) . ")");

					}

				}

			}

		}

	}
?>