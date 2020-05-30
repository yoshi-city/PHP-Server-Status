<?php 
//Suppress errors, change to E_ALL for debugging
ERROR_REPORTING(0);
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="/phpss/css/tables.css" />
	</head>
		<table class="table-fill">	
			<tbody style="height:50px;">
				<?php		
					//Data required:
						//ISO Country code for flag 	(stored in 'img/flags/')
						//Service name					(Can be HTML for links)
						//Domain 						(If IP provided, only used for display purposes)
						//IP 							(Takes priority over domain if provided. Leave empty to use domain)
						//Port number 					(0-65535)
						//Should the port be displayed? (true/false)
						//Interval/Cache expiry time	(time in seconds, must not be less than your cronjob time if you exclusively use that to use that to record status)
						//Bypass cache 					(set to false on your viewing page, true on your cronjob)
					
					require_once "phpss/PHPServerStatus.class.php";
					$phpssobj = new PHPServerStatus();
					echo $phpssobj -> GetStatus("gb", "local server",  "localhost", "", 80, false, 300, false);
					echo $phpssobj -> GetStatus("fr", "external server",  "google.fr", "", 80, false, 300, false);			
				?>
			</tbody>
		</table>

		<Br>

		<?php
			require_once "phpss/showstats.class.php";
			$showobj = new showstats;
			echo $showobj -> ShowStats();
		?>
	</body>
</html>