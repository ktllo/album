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
if(!file_exists('data/'.$albumDir.'/info.php'))
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
	$( "#edit" ).button({
		text: false,
		icons: {
			primary: "ui-icon-pencil"
		}
	}).click(function(){
		window.location = '/edit/<?php echo $albumDir;?>/<?php echo $image['path'];?>';
	});
	$( "#accordion" ).accordion({
      collapsible: true
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
			<button id="edit">Edit</button>
		</div>
		<hr>
		<div style="width:80%; margin:auto;">
			<img src="/data/<?php echo $albumDir;?>/<?php echo $image['path'];?>" style="width:100%;">
		</div>
		<div style="width:90%; margin:auto;" id="accordion">
			<h3>Description</h3>
			<div>
				<?php echo $image['desc']; ?>
			</div>
			<h3>EXIF</h3>
			<div>
<?php
$exif = exif_read_data ("data/$albumDir/{$image['path']}",'ANY_TAG');
?>
				<table style="width:100%">
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
			</div>
		</div>
		<div style="width:90%; margin:auto;"><a href="/data/<?php echo $albumDir;?>/<?php echo $image['path'];?>">Download full image</a></div>

	</body>
</html>
