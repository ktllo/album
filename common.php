<?php
function initAlbum($dir){
	$file = fopen('data/'.$dir.'/info.php','w');
	$date = date('d M Y');
	fwrite($file, "<?php\n".'$'."albumName = 'Untitled Album';\n".'$'."created = '$date';".'$'."desc=\"\";?>");
	fclose($file);
}
function createThumbnail($album,$img){
	$thumbnail_width = 280;
	$thumbnail_height = 210;
	$arr_image_details = getimagesize('data/'.$album.'/'.$img);
	$original_width = $arr_image_details[0];
    $original_height = $arr_image_details[1];
    if ($original_width > $original_height) {
        $new_width = $thumbnail_width;
        $new_height = intval($original_height * $new_width / $original_width);
    } else {
        $new_height = $thumbnail_height;
        $new_width = intval($original_width * $new_height / $original_height);
    }
    $dest_x = intval(($thumbnail_width - $new_width) / 2);
    $dest_y = intval(($thumbnail_height - $new_height) / 2);
    if ($arr_image_details[2] == 1) {
        $imgt = "ImageGIF";
        $imgcreatefrom = "ImageCreateFromGIF";
    }
    if ($arr_image_details[2] == 2) {
        $imgt = "ImageJPEG";
        $imgcreatefrom = "ImageCreateFromJPEG";
    }
    if ($arr_image_details[2] == 3) {
        $imgt = "ImagePNG";
        $imgcreatefrom = "ImageCreateFromPNG";
    }
    if ($imgt) {
        $old_image = $imgcreatefrom('data/'.$album.'/'.$img);
        $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
        imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
        $imgt($new_image, 'data/'.$album.'/meta/'.$img);
    }
    
}
function initImage($album,$img){
	$file = fopen('data/'.$album.'/meta/'.$img.'.php','w');
	$date = date('d M Y');
	fwrite($file, "<?php\n".'$'."title = 'Untitled';\n".'$'."created = '$date';".'$'."desc=\"\";?>");
	fclose($file);
}