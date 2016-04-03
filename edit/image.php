<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Edit Image"');
    header('HTTP/1.0 401 Unauthorized');
?><!DOCTYPE html>
<html>
	<head>
		<title>Sorry</title>
	</head>
	<body>
		<h1>HTTP 401 - UNAUTHORIZED</h1>
	</body>
</html>
<?php
    exit;
} else {
    echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
    echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
}
?>

