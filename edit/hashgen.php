<!DOCTYPE html>
<html>
	<head>
		<title>Hashgen</title>
	</head>
	<body>
	<?php if(isset($_POST['user']) && isset($_POST['password'])){ ?>
		<p>Please copy the following line into the config and replace (if any) pervious password for the user</p>
		<pre>
$user[] = array('user' => '<?php echo $_POST['user'];?>','password' => '<?php echo password_hash($_POST['password'],PASSWORD_DEFAULT);?>');</pre><?php } ?>
		<form action="/edit/hashgen.php" method="post">
			<label>User<input name="user" type="text"></label></br>
			<label>Password<input name="password" type="password"></label>
			<input type="submit" name="submit" value="Submit">
		</form>
	</body>
</html>
