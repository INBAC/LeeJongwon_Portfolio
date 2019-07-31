<?php

$filename = $_GET['filename'];
$check_dir = $_GET['check'];
//$filename1 = basename($filename);
//e//cho $filename1;
//echo $filename;

if($check_dir == 0){
$path = "../../up/upfile/".$filename;
}else{
$path = "../../up/upfile_n/".$filename;
}

$filename_cut = substr($filename, 29);
$filename_trim = trim($filename_cut);
$filename1 = iconv("UTF-8", "cp949", $filename_trim);

//$filename2 = trim($filename1);

//echo $filename;

header("Content-type: application/octet-stream");
header("Content-Length: ".filesize("$path"));
header("Content-Disposition: attachment; filename=$filename1");
header("Content-Transfer-Encoding: binary");
//Header('Content-Disposition: attachment; filename='.iconv('UTF-8','CP949',$file));

$fp = fopen($path, "rb");
fpassthru($fp);
fclose($fp);

?>
