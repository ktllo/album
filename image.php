<?php
include_once 'common.php';
$albumDir = $_GET['album'];
$file = $_GET['image'];
$info = file_exists('data/'.$albumDir.'/meta/'.$file.'.php');
if(!$info)
	initImage($albumDir,$file);
include 'data/'.$albumDir.'/meta/'.$file.'.php';
$image['name'] = $title;
$image['created'] = $created;
$image['desc'] = $desc;
$image['path'] = $file;
if(file_exists('data/'.$albumDir.'/info.php'))
	initAlbum($albumDir);
include_once 'data/'.$albumDir.'/info.php';
$fileList = scandir('data/'.$albumDir);
foreach($fileList as $file){
	$ext = strtok( $file , '.');
	while(true){
		$token = strtok('.');
		if($token === FALSE)
			break;
		$ext = $token;
	}
	$ext = strtolower($ext);
	if($ext == 'jpg' || $ext == 'png'){
		$imgList[] = $file;
	}
}
$status = -1;
$prev = null;
$next = null;
foreach($imgList as $img){
	if($status == 1){
		$next = $img;
		break;
	}else if($img == $image['path']){
		$status = 1;
	}else{
		$prev = $img;
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $albumName;?> - <?php echo $image['name'];?></title>
		<script src="/jquery-2.2.2.min.js"></script> 
		<script src="/jquery-ui.js"></script> 
		<link rel="stylesheet" type="text/css" href="/jquery-ui.css">
<script>
$(function() {
	<?php if($prev!=null){ ?>
	$( "#first" ).button({
		text: false,
		icons: {
			primary: "ui-icon-arrowthickstop-1-w"
		}
	}).click(function(){
		window.location = '<?php echo $imgList[0];?>';
	});
	$( "#prev" ).button({
		text: false,
		icons: {
			primary: "ui-icon-arrowthick-1-w"
		}
	}).click(function(){
		window.location = '<?php echo $prev;?>';
	});
	<?php }if($next!=null){ ?>
	$( "#last" ).button({
		text: false,
		icons: {
			primary: "ui-icon-arrowthickstop-1-e"
		}
	}).click(function(){
		window.location = '<?php echo $imgList[count($imgList)-1];?>';
	});
	$( "#next" ).button({
		text: false,
		icons: {
			primary: "ui-icon-arrowthick-1-e"
		}
	}).click(function(){
		window.location = '<?php echo $next;?>';
	});
	<?php } ?>
	$( "#random" ).button({
		text: false,
		icons: {
			primary: "ui-icon-shuffle"
		}
	}).click(function(){
		window.location = '<?php echo $imgList[rand(0,count($imgList)-1)];?>';
	});
});
</script>
	</head>
	<body>
		<h1><?php echo $image['name'];?></h1>
		<div><a href="/">Home</a> &gt; <a href="./"><?php echo $albumName;?></a> &gt; <?php echo $image['name'];?></div>
		<div id="toolbar" class="ui-corner-all" style="text-align:center;">
<?php if($prev!=null){ ?>
			<button id="first">First</button>
			<button id="prev">Previous</button>
<?php }?>
			<button id="random">Random</button>
<?php if($next!=null){ ?>
			<button id="next">Next</button>
			<button id="last">Last</button>
<?php } ?>
		</div>
		<hr>
		<div style="width:80%; margin:auto;">
			<img src="/data/<?php echo $albumDir;?>/<?php echo $image['path'];?>" style="width:100%;">
		</div>
		<div style="width:90%; margin:auto;"><?php echo $image['desc'];?></div>
		<div style="width:90%; margin:auto;"><a href="/data/<?php echo $albumDir;?>/<?php echo $image['path'];?>">Download full image</a></div>

	</body>
</html>
