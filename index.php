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
	$datadir = scandir('data');
	$datas = array();
	foreach($datadir as $dir){
		if( $dir[0] == '.' ){
			continue;
		}
		$files = scandir('data/'.$dir);
		$isInit = false;
		$fileCount  = 0;
		$albumName = '';
		foreach($files as $file){
			if($file=='info.php'){
				$isInit = true;
				include 'data/'.$dir.'/info.php';
			}else{
				$ext = strtok( $file , '.');
				while(true){
					$token = strtok('.');
					if($token === FALSE)
						break;
					$ext = $token;
				}
				$ext = strtolower($ext);
				if($ext == 'jpg' || $ext == 'png')
					$fileCount++;
			}
		}
		if(!$isInit){
			$file = fopen('data/'.$dir.'/info.php','w');
			$date = date('d M Y');
			fwrite($file, "<?php\n".'$'."albumName = 'Untitled Album';\n".'$'."created = '$date'?>");
			$created = $date;
			fclose($file);
			$albumName = 'Untitled Album';
		}
		$entry['name'] = $albumName;
		$entry['created'] = $created;
		$entry['size']  = $fileCount;
		$entry['dir'] = $dir;
		$datas[] = $entry;
	}
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
