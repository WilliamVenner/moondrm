<?php
	include("../moondrm.php");
	$MoonDRM = new MoonDRM();
	
	if (isset($_POST["generatehash"])) {
		if (is_numeric($_POST["hashscriptid"])) {
			$q = $MoonDRM -> query("SELECT NULL FROM `scripts` WHERE `script_id`=" . $MoonDRM->e($_POST["hashscriptid"]));
			if ($q -> num_rows == 0)
				$generatehasherror = "That script isn't on MoonDRM.";
			else {
				$q = $MoonDRM -> query("SELECT `hashnumber` FROM `hashes` WHERE `steamid64`=" . $MoonDRM->e($_GET["user"]) . " AND `script_id`=" . $MoonDRM->e($_POST["hashscriptid"]));
				if ($q -> num_rows == 1) {
					$q = $q -> fetch_array();
					$MoonDRM -> query("UPDATE `hashes` SET `hashnumber`=`hashnumber` + 1, `hash`=" . $MoonDRM->e($MoonDRM -> GenerateHash($_GET["user"],intval($q["hashnumber"]) + 1)) . " WHERE `steamid64`=" . $MoonDRM->e($_GET["user"]) . " AND `script_id`=" . $MoonDRM->e($_POST["hashscriptid"]));
				} else {
					$MoonDRM -> query("INSERT INTO `hashes` (`hash`,`steamid64`,`script_id`) VALUES(" . $MoonDRM->e($MoonDRM -> GenerateHash($_GET["user"],$_POST["hashscriptid"],1)) . "," . $MoonDRM->e($_GET["user"]) . "," . $MoonDRM->e($_POST["hashscriptid"]) . ")");
				}
				header("LOCATION: /form.php?redirect=" . urlencode($_SERVER["REQUEST_URI"]));
			}
		} else
			$generatehasherror = "That wasn't a Script ID.";
	}
	
	ob_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>MoonDRM &middot; <?php echo(htmlentities($_GET["user"])); ?></title>
		<link rel="stylesheet" href="/assets/css/user.css">
		<script type="text/javascript" src="/assets/js/lib/jquery-3.0.0.min.js"></script>
	</head>
	<body>
		
		<?php include_once("inc/header.php"); ?>
		
		<div id="content">
			
			<?php include_once("inc/global_content.php"); ?>
			
			<?php include_once("inc/footer.php"); ?>
			
			<?php
				
				$q = $MoonDRM -> query("SELECT * FROM `users` WHERE `steamid64`=" . $MoonDRM->e($_GET["user"]));
				if ($q -> num_rows == 0) {
					
					$q2 = $MoonDRM -> query("SELECT NULL FROM `hashes` WHERE `steamid64`=" . $MoonDRM->e($_GET["user"]));
					if ($q2 -> num_rows == 0) {
						echo("<div class='announcement red'>This user was not found on the MoonDRM system.</div>");
						die();
					}
				} else {
					$q = $q -> fetch_array();
					$q["steamtable"] = json_decode($q["steamtable"],true);
				}
				
			?>
			
			<div id="user">
				
				<iframe src="https://steamid.venner.io/?q=<?php echo(htmlentities($_GET["user"])); ?>"></iframe>
				
			</div>
			
			<div id="info">
				
				<?php
					
					$admin_query = $MoonDRM -> query("SELECT NULL FROM `developers` WHERE `steamid64`=" . $MoonDRM->e($MoonDRM -> SteamID64) . " AND `admin`=TRUE");
					
					if ($admin_query -> num_rows > 0) {
					
				?>
				
					<div id="admin-buttons">
					
						<?php
						
							$q = $MoonDRM -> query("SELECT * FROM `bans` WHERE `steamid64`=" . $MoonDRM->e($_GET["user"]) . " AND (`expires`=NULL OR `expires` > CURRENT_TIMESTAMP) LIMIT 1");
							$qe;
							
							if ($q -> num_rows > 0) {
								
								$settings = $MoonDRM -> Settings;
								
								$qe = $q -> fetch_array();
								
								?> <div class="announcement red">This user has been banned from MoonDRM <?php echo($qe["expires"] == 0 ? "forever" : ("until " . htmlentities(date($settings["Date/Time Format"],intval($qe["expires"]))))); ?> for reason:<br><?php echo(htmlentities($qe["reason"])); ?></div> <?php
								
							}
							
						?>
						
						<h2>Actions</h2>
						
						<?php
						
							if ($q -> num_rows == 0) {
								
								?> <div class="button red" id="ban_button">Ban</div> <?php
								
							} else {
								
								?> <input type="submit" name="unban" class="button green">Unban</input> <?php
								
							}
						
							$q = $MoonDRM -> query("SELECT `transactionid` FROM `developers` WHERE `steamid64`=" . $MoonDRM->e($_GET["user"]) . " AND `transactionid` IS NOT NULL");
							if ($q -> num_rows > 0) {
						
								$q = $q -> fetch_array();
								
								?> <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id=<?php echo(htmlentities($q["transactionid"])); ?>"><div class="button blue">Latest PayPal transaction</div></a> <?php
								
							}
							
						?>
					
					</div>
				
				<?php } ?>
					
				<h2>Hashes</h2>
				
				<table id="hashes-table">
					<thead>
						<tr>
							<th>Script ID</th>
							<th>Revoked</th>
							<th>Hash Number</th>
							<th>Hash</th>
						</tr>
					</thead>
					<tbody>
						<?php
							
							$extra = "";
							
							if ($admin_query -> num_rows > 0)
								$extra = " AND `developer`=" . $MoonDRM->e($MoonDRM -> SteamID64);
							
							$q = $MoonDRM -> query("SELECT * FROM `hashes` WHERE `steamid64`=" . $MoonDRM->e($_GET["user"]) . $extra);
							if ($q -> num_rows == 0) {
								
								?> <tr><td colspan="2">No hashes (that you are allowed to see) have been found.</td></tr> <?php
								
							} else {
								
								while($row = $q -> fetch_assoc()) {
									
									?> <tr><td><?php echo(htmlentities($row["script_id"])); ?></td><td><?php echo(($row["revoked"] == "1" ? "Yes" : "No")); ?></td><td><?php echo(htmlentities($row["hashnumber"])); ?></td><td><?php echo(htmlentities($row["hash"])); ?></td></tr> <?php
									
								}
								
							}
							
						?>
					</tbody>
				</table>
				
				<?php
				
					if ($admin_query -> num_rows > 0) { ?>
				
						<form method="POST">
							
							<?php
								
								if (isset($generatehasherror)) {
									
									?> <div class="form-error"><?php echo(htmlentities($generatehasherror)); ?></div> <?php
									
								}
								
							?>
							
							<input type="number" placeholder="Script ID" name="hashscriptid" style="margin-bottom:5px" value="<?php echo(isset($_POST["hashscriptid"]) ? $_POST["hashscriptid"] : ""); ?>"><br>
							
							<input type="submit" name="generatehash" value="(Re)Generate Hash" class="button blue" style="margin-bottom:15px">
							
						</form>
					
				<?php } ?>
				
				<h2>Known used scripts</h2>
				
				<div id="scripts-container">
					
					<?php
						
						$used = [];
						$q = $MoonDRM -> query("SELECT * FROM `scripts_used` WHERE `steamid64`=" . $MoonDRM->e($_GET["user"]));
						
						if ($q -> num_rows == 0) {
							
							?> <div class="announcement red">This user hasn't used any scripts.</div> <?php
							
						} else {
							
							$used = [];
							while($row = $q -> fetch_assoc()) {
								array_push($used,$row["script_id"]);
							}
							
							?> <div class="container-fluid">
								
								<div class="row">
									
									<?php
										
										foreach($used as $script_id) {
										
											$q = $MoonDRM -> query("SELECT * FROM `scripts` WHERE `script_id`=" . $MoonDRM->e($script_id)) -> fetch_array(); ?>
										
											<div class="col-md-6 script-column">
												
												<div class="script">
													
													<div class="script-title">
														
														<?php echo(htmlentities($q["name"])); ?>
														
													</div>
													
													<img class="script-image" src="<?php echo(htmlentities($MoonDRM -> GetScriptImage($script_id))); ?>"/>
													
													<a href="/scripts/<?php echo(htmlentities($script_id)); ?>"><div class="button green">View</div></a>
													
												</div>
												
											</div>
										
										<?php }
									
									?>
									
								</div>
								
							</div>
							
						<?php } ?>
					
				</div>
				
			</div>
			
		</div>
		
	</body>
</html>
<?php ob_end_flush(); ?>