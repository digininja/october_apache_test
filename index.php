<pre>
<?php
if (array_key_exists ("SERVER_SOFTWARE", $_SERVER)) {
	print "Server software is: " . $_SERVER["SERVER_SOFTWARE"];
	print "\n";

	if ($_SERVER["SERVER_SOFTWARE"] == "Apache") {
		$current_uri = $_SERVER['REQUEST_URI'];
		$check_dir = $current_uri . "apache_test_dir/rewrite_test";

		$ch = curl_init();

		$protocol = "http://";
		if(isset($_SERVER['HTTPS'])) {
			if ($_SERVER['HTTPS'] == "on") {
				$protocol = "https://";
			}
		}

		$url = $protocol . $_SERVER['HTTP_HOST'] . $check_dir;
		curl_setopt($ch, CURLOPT_URL, $url);
		print "Checking the URL: " . $url;
		print "\n\n";

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$output = curl_exec($ch);
		// var_dump (curl_errno($ch));

		if (!curl_errno($ch)) {
			$response_code = curl_getinfo ($ch, CURLINFO_HTTP_CODE);
			// var_dump ($response_code);
			switch ($response_code) {
			case 200:
				// var_dump (trim ($output));
				if (trim($output) == "this page has been rewritten") {
					print "rewrite worked, got good response";
					print "\n";
				} else {
					print "rewrite failed";
					print "\n";
				}
				break;
			case (404):
				print "got 404, rewrite failed";
				print "\n";
				break;
			case (500):
				print "got 500, looks like .htaccess is not allowed";
				print "\n";
				break;
			default:
				print "Unexpected response code " . $response_code . ", needs checking";
				print "\n";
			}
		} else {
			print "error with curl";
			print "\n";
		}

		curl_close($ch);     
	} else {
		print "Not Apache";
	}
}
?>
