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
include_once 'data/'.$albumDir.'/info.php';?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $albumName;?></title>
		<script src="jquery-2.2.2.min.js"></script> 
		<script src="jquery-ui.js"></script> 
		<link rel="stylesheet" type="text/css" href="jquery-ui.css">
	</head>
	<body>
		<h1><?php echo $image['name'];?></h1>
		<div><a href="/">Home</a> &gt; <a href="./"><?php echo $albumName;?></a> &gt; <?php echo $image['name'];?></div>
		<hr>
		<img src="/data/<?php echo $albumDir;?>/<?php echo $image['path'];?>" style="margin:auto;width:85%;">
		<div><?php echo $image['desc'];?></div>
