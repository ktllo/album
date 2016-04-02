<?php
include_once 'common.php';
$albumDir = $_GET['album'];
if(!file_exists('data/'.$albumDir.'/info.php'))
	initAlbum($albumDir);
include_once 'data/'.$albumDir.'/info.php';
if(!file_exists('data/'.$albumDir.'/meta'))
	mkdir('data/'.$albumDir.'/meta');
$files = scandir('data/'.$albumDir);
$images = array();
foreach($files as $file){
	$ext = strtok( $file , '.');
	while(true){
		$token = strtok('.');
		if($token === FALSE)
			break;
		$ext = $token;
	}
	$ext = strtolower($ext);
	if($ext == 'jpg' || $ext == 'png'){
		$thumbnail = file_exists('data/'.$albumDir.'/meta/'.$file);
		$info = file_exists('data/'.$albumDir.'/meta/'.$file.'.php');
		if(!$thumbnail)
			createThumbnail($albumDir,$file);
		if( filemtime('data/'.$albumDir.'/'.$file) > filemtime('data/'.$albumDir.'/meta/'.$file)){
			createThumbnail($albumDir,$file);
		}
		if(!$info)
			initImage($albumDir,$file);
		unset($imageMetaVersion);
		include 'data/'.$albumDir.'/meta/'.$file.'.php';
		if( empty($imageMetaVersion) || $imageMetaVersion < 1 ){
			updateImageMeta($albumDir,$file);
			include 'data/'.$albumDir.'/meta/'.$file.'.php';
		}
		$image['name'] = $title;
		$image['created'] = $created;
		$image['desc'] = $desc;
		$image['path'] = $file;
		$images[] = $image;
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $albumName;?></title>
		<script src="/jquery-2.2.2.min.js"></script> 
		<script src="/jquery-ui.js"></script> 
		<link rel="stylesheet" type="text/css" href="/jquery-ui.css">
		<style>
table.flow{
	display : inline-block;
	width : 300px;
	height : 400px;
	border-width: 1px;
	border-color: #ddd;
	border-style: solid;
	border-radius: 5px;
}
table.flow td{
	margin-left: auto;
	margin-right: auto;
	padding: 5px;
	width: 290px;
	height: 390px;
	vertical-align: top;
}
table.flow td img{
	width: 280px;
	height: 210px;
}
table.flow td div.desc{
	width: 280px;
	height: 100px;
	overflow: auto;
}

		</style>
	</head>
	<body>
		<h1><?php echo $albumName;?></h1>
		<div><a href="/">Home</a> &gt; <?php echo $albumName;?></div>
		<div><?php echo $desc;?></div>
		<hr>
		<div style="text-align:center;">
<?php foreach($images as $image){ ?>
<table class="flow">
	<tr>
		<td>
			<a href="/<?php echo $albumDir;?>/<?php echo $image['path'];?>">
				<img src="/data/<?php echo $albumDir;?>/meta/<?php echo $image['path'];?>">
			</a>
			<hr>
			<div style="font-weight:bold;"><?php echo $image['name'];?></div>
			<div style="font-style: italic;">Created <?php echo $image['created'];?></div>
			<div class="desc"><?php echo $image['desc'];?></div>
		</td>
	</tr>
</table>
<?php } ?>
		</div>
	</body>
</html>
