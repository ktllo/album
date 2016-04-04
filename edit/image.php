<?php
include_once '../config.php';
include_once '../user.php';
if(gethostname() != MASTER_HOST){
	header('Location: https://'.MASTER_FORCE_ACCESS.$_SERVER['REQUEST_URI']);
	exit;
}
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
    $auth_user = $_SERVER['PHP_AUTH_USER'];
	$auth_pass = $_SERVER['PHP_AUTH_PW'];
	$ok = false;
	foreach($user as $u){
		if(strtolower($u['user']) == strtolower($auth_user)){
			if(password_verify($auth_pass,$u['password'])){
				$ok = true;
			}
			break;
		}
	}
	if(!$ok){
		password_hash('auth_pass',PASSWORD_DEFAULT);
		header('WWW-Authenticate: Basic realm="Edit Image"');
   	 	header('HTTP/1.0 401 Unauthorized');?><!DOCTYPE html>
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
	}
}
function replaceSpecialChar($str){
	$str = str_replace('\\','\\\\',$str);
	$str = str_replace('\'','\\\'',$str);
	return $str;
}
include_once '../common.php';
$albumDir = $_GET['album'];
$file = $_GET['image'];
if(isset($_POST['title'])){
	$title = $_POST['title'];
	$created = $_POST['created'];
	$desc = $_POST['desc'];
	$f = fopen('../data/'.$albumDir.'/meta/'.$file.'.php','w');
	fwrite($f, "<?php\n");
	fwrite($f, '$title=\''.replaceSpecialChar($title).'\';');
	fwrite($f, '$created=\''.replaceSpecialChar($created).'\';');
	fwrite($f, '$desc=\''.replaceSpecialChar($desc).'\';');
	fwrite($f, '$albumMetaVersion=1;');
	fclose($f);
}

include '../data/'.$albumDir.'/meta/'.$file.'.php';
$image['name'] = $title;
$image['created'] = $created;
$image['desc'] = $desc;
$image['path'] = $file;
include_once '../data/'.$albumDir.'/info.php';
$fileList = scandir('../data/'.$albumDir);
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
?><!DOCTYPE html>
<html>
	<head>
		<title>Edit photo detail</title>
		<script src="/jquery-2.2.2.min.js"></script> 
		<script src="/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="/jquery-ui.css">
		<script src="/bower_components/trumbowyg/dist/trumbowyg.min.js"></script>
		<link rel="stylesheet" href="/bower_components/trumbowyg/dist/ui/trumbowyg.min.css">
<script>
$(function() {
	$('#desc').trumbowyg({
    btns: [
        ['viewHTML'],
        ['formatting'],
        'btnGrp-semantic',
        ['superscript', 'subscript'],
        ['link'],
        'btnGrp-justify',
        'btnGrp-lists',
        ['horizontalRule'],
        ['removeformat'],
        ['fullscreen']
    ]
	});
	$( "#created" ).datepicker({
		dateFormat : 'd MM yy',
		maxDate : 0
	});
});
</script>
	</head>
	<body>
		<div style="width:80%; margin:auto;">
			<img src="/data/<?php echo $albumDir;?>/<?php echo $image['path'];?>" style="width:100%;">
		</div>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
		Photo title: <input name="title" type="text" value="<?php echo $title;?>"><br>
		Created on: <input name="created" type="text" value="<?php echo $created;?>" id="created"><br>
		<textarea name="desc" id="desc"><?php echo $desc;?></textarea>
		<input name="submit" type="submit" value="Submit">
		</form> <table style="width:100%"><?php $exif = exif_read_data ("../data/$albumDir/{$image['path']}",'ANY_TAG'); ?>
					<tr><td colspan="2" style="font-size:x-large;">EXIF data</td></tr>
					<tr><th style="width:25%;">Parametre</th><th>Value</th></tr>
<?php if(isset($exif['Make'])){ ?><tr><td>Camrea make</td><td><?php echo $exif['Make'];?></td></tr><?php } ?>
<?php if(isset($exif['Model'])){ ?><tr><td>Model</td><td><?php echo $exif['Model'];?></td></tr><?php } ?>
<?php if(isset($exif['ExposureTime'])){ ?><tr><td>Shutter</td><td><?php echo $exif['ExposureTime'];?></td></tr><?php } ?>
<?php if(isset($exif['COMPUTED']['ApertureFNumber'])){ ?><tr><td>Aperture</td><td><?php echo $exif['COMPUTED']['ApertureFNumber'];?></td></tr><?php } ?>
<?php if(isset($exif['ISOSpeedRatings'])){ ?><tr><td>ISO</td><td><?php echo $exif['ISOSpeedRatings'];?></td></tr><?php } ?>
<?php if(isset($exif['ExposureProgram'])){ ?><tr><td>Exposure Program</td><td><?php switch( $exif['ExposureProgram']){
case 0: echo 'Unknown'; break;
case 1: echo 'Manual'; break;
case 2: echo 'Normal'; break;
case 3: echo 'Aperture Priority'; break;
case 4: echo 'Shutter Priority'; break;
case 5: echo 'Creative'; break;
case 6: echo 'Action'; break;
case 7: echo 'Portrait'; break;
case 8: echo 'Landscape'; break;
}?></td></tr><?php } ?>
<?php if(isset($exif['MeteringMode'])){ ?><tr><td>Metering</td><td><?php switch( $exif['MeteringMode']){
case 0: echo 'Unknown'; break;
case 1: echo 'Average'; break;
case 2: echo 'Centre Weighted Average'; break;
case 3: echo 'Spot'; break;
case 4: echo 'Multispot'; break;
case 5: echo 'Pattern'; break;
case 6: echo 'Partial'; break;
default: echo ' Other';
}?></td></tr><?php } ?>
<?php if(isset($exif['LightSource'])){ ?><tr><td>Light Source</td><td><?php switch( $exif['LightSource']){
case 0: echo 'Unknown'; break;
case 1: echo 'Daylight'; break;
case 2: echo 'Fluorescent'; break;
case 3: echo 'Tungsten'; break;
case 4: echo 'Flash'; break;
case 9: echo 'Fine weather'; break;
case 10: echo 'Cloudy weather'; break;
case 11: echo 'Shade'; break;
case 12: echo 'Daylight fluorescent'; break;
case 13: echo 'Day white fluorescent'; break;
case 14: echo 'Cool white fluorescent'; break;
case 15: echo 'White fluorescent'; break;
case 17: echo 'Standard light A'; break;
case 18: echo 'Standard light B'; break;
case 19: echo 'Standard light C'; break;
case 20: echo 'D55'; break;
case 21: echo 'D65'; break;
case 22: echo 'D75'; break;
case 23: echo 'D50'; break;
case 24: echo 'ISO studio tungsten'; break;
default: echo ' Other';
}?></td></tr><?php } ?>

<?php if(isset($exif['Flash'])){ ?><tr><td>Flash</td><td><?php echo $exif['Flash'] & 1 == 1?"Yes":"No";?></td></tr><?php } ?>

				</table>
				<a href="/<?php echo $albumDir;?>/<?php echo $image['path'];?>">Back</a>
	</body>
</html>

