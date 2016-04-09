<!DOCTYPE html>
<html>
	<head>
		<title>Leo's Album</title>
		<script src="jquery-2.2.2.min.js"></script> 
		<script src="jquery-ui.js"></script> 
		<link rel="stylesheet" type="text/css" href="jquery-ui.css">
	</head>
	<body>
		<h1>Welcome to Leo's album</h1>
<?php
include_once 'common.php';

?>
	
		<p style="font-weight:bold;">There are <?php
	$count = count($datas);
	echo $count;
	echo $count==1?" album":' albums'; ?> in the album set.</p>
		<ul><?php foreach($datas as $data){ ?>
			<li><a href="<?php echo $data['dir'];?>"><?php echo $data['name'];?></a>(Created <?php echo $data['created'];?>, <?php echo $data['size'];?> photos)</li><?php } ?>
		</ul>
	</body>
</html>
