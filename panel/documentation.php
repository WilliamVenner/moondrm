<?php
	include("../moondrm.php");
	$MoonDRM = new MoonDRM();
	ob_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>MoonDRM &middot; Documentation</title>
		<link rel="stylesheet" href="/assets/css/settings.css">
		<script type="text/javascript" src="/assets/js/lib/jquery-3.0.0.min.js"></script>
		<script type="text/javascript" src="/assets/js/lib/ace-lua/ace.js"></script>
	</head>
	<body>

		<?php include_once("inc/header.php"); ?>

		<div id="content">

			<?php include_once("inc/global_content.php"); ?>

			<?php include_once("inc/footer.php"); ?>

			Please do not share any information on this page with people who aren't allowed here.<br><br>

			MoonDRM has two "parts" to it.<br>
			The first part is the webhook. The webhook is what happens when a user downloads your script. It polls MoonDRM and receives a unique ID that identifies the user when trying to receive the Lua code to your script from MoonDRM.<br>
			The second part is actually receiving and executing the Lua code. MoonDRM does all of this for you. We won't release any secrets about how it works, but we'll tell you how to do it!<br><br>

			First, you'll need to set up your script's download.<br>

			To do this, you can easily fill out the form below:<br><br>

			<form method="POST">

				<label>Script ID: <input type="number" name="script_id"></label><br><br>

				<label>Script (Short) Name: <input type="text" name="name"></label><br><br>

				<label>Script Version: <input type="text" name="version"></label><br><br>

				<input type="submit" class="button blue" name="generate" value="Generate Loader">

			</form>

			<?php

				if (isset($_POST["generate"])) {

					?><div class="code" data-readonly="y"><?php

						$code = file_get_contents("../lua/moondrm-loader.min.lua");

						echo(sprintf($code,intval($_POST["script_id"]),$_POST["version"],'{{ web_hook "https://api.moondrm.com/webhook/" "' . $MoonDRM -> API_Key . '" }}',"{{ user_id }}",$_POST["name"]));

					?></div><?php

				}

			?>

		</div>

	</body>
</html>