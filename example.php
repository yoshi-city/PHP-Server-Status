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
						//Interval/Cache expiry time	(time in seconds)
		
					require_once("phpss/PHPServerStatus.class.php");

					$netobj = new PHPServerStatus("gb", "internal server", "localhost", "127.0.0.1", 80, true, 300);
					echo  $netobj -> GetStatus();

					$netobj = new PHPServerStatus("fr", "external server", "google.fr", "", 80, false, 600);
					echo  $netobj -> GetStatus();
				?>
			</tbody>
		</table>
	</body>
</html>