<?php

header('X-Accel-Buffering: no');


if (isset($_GET['run_update'])) {


	$download_url 	= "https://hydro.bot/files/hydrobot.zip";
	
	
	
	
	
	
	
	echo "Running updater, do not leave this page. <br /><br />";
	
	ob_flush();
	flush();
	sleep(1);
	
	
	
	
	
	
	
	
	echo "Step 1 - Download<br />";
	
	file_put_contents('/var/www/html/hydrobot.zip',file_get_contents( "$download_url" ));
	
	ob_flush();
	flush();
	sleep(1);
	
	
	
	
	
	
	
	
	
	
	echo "Step 2 - Delete current portal version<br />";
	
	system("rm -rf ".escapeshellarg('/var/www/html/portal/'));
	
	
	ob_flush();
	flush();
	sleep(1);
	
	
	
	
	
	
	
	
	
	
	
	echo "Step 3 - Deflate<br />";
	
	$zip = new ZipArchive;
	if ($zip->open('hydrobot.zip') === TRUE) {
		$zip->extractTo('/var/www/html/portal/');
		$zip->close();
	}
	
	ob_flush();
	flush();
	sleep(1);
	
	
	
	
	
	
	
	
	
	echo "Step 4  - Cleaning up<br />";
	
	unlink('/var/www/html/hydrobot.zip');
	
	
	ob_flush();
	flush();
	sleep(1);
	
	
	
	
	
	
	
	echo "Step 5  - Finished update<br />";
	
	ob_flush();
	flush();
	sleep(1);
	
	
	
	
	
	echo "Step 6 - Redirecting to new version...<br />";
	
	ob_flush();
	flush();
	sleep(1);
	
	?>
	
	<script>
	window.location = "/portal/update.php";
	</script>

	<?php
	
	exit;
}