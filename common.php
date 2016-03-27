<?php
function initAlbum($dir){
	$file = fopen('data/'.$dir.'/info.php','w');
	$date = date('d M Y');
	fwrite($file, "<?php\n".'$'."albumName = 'Untitled Album';\n".'$'."created = '$date'?>");
	fclose($file);
}
