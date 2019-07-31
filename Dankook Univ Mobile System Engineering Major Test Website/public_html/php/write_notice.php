<?php
session_start();

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$title = $_POST['title'];
$content = $_POST['content'];
$date1 = date("Y.m.d");
$date2 = date("H:i:s");

$sort = $_POST['notice_type'];
$hits = 0;

$file1 = $_FILES['upfile1']['tmp_name'];
$filename1 = $_FILES['upfile1']['name'];

$file2 = $_FILES['upfile2']['tmp_name'];
$filename2 = $_FILES['upfile2']['name'];

$file3 = $_FILES['upfile3']['tmp_name'];
$filename3 = $_FILES['upfile3']['name'];

if($file1 != NULL){
  $f1 = $user_id._.$date1._.$date2._.$filename1;
  $folder = "../../up/upfile_n/".$f1;
  move_uploaded_file($file1 , $folder);
}

if($file2 != NULL){
  $f2 = $user_id._.$date1._.$date2._.$filename2;
  $folder = "../../up/upfile_n/".$f2;
  move_uploaded_file($file2 , $folder);
}

if($file3 != NULL){
  $f3 = $user_id._.$date1._.$date2._.$filename3;
  $folder = "../../up/upfile_n/".$f3;
  move_uploaded_file($file3 , $folder);
}

if($title==NULL||$content==NULL)
{
        echo "<script>alert('빈칸을 모두 입력해주세요!.')";
        echo "window.location.replace('../newnotice.php');</script>";
        exit();
}

$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');

if(mysqli_connect_errno($mysqli)){
            echo "연결실패";
    }else{
            echo "성공";
    }

$check = "INSERT INTO notice VALUES" . "(DEFAULT,'$title','$sort', '$hits','$user_name',now(),'$content','$f1','$f2','$f3','$date1','$date2',DEFAULT)";
$result = $mysqli->query($check);

if(!result){
	echo " fail";
}
echo "<script>window.location.replace('../notice.php');</script>";

exit;
?>
