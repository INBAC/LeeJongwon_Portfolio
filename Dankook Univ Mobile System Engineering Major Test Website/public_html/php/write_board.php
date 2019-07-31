<?php
session_start();

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$title = $_POST['title'];
$content = $_POST['content'];
$password = $_POST['board_pw'];
$date1 = date("Y.m.d");
$date2 = date("H:i:s");

$sort = $_POST['board_type'];
$sub_sort = $_POST['head_type'];
$hits = 0;

$file1 = $_FILES['upfile1']['tmp_name'];
$filename1 = $_FILES['upfile1']['name'];

$file2 = $_FILES['upfile2']['tmp_name'];
$filename2 = $_FILES['upfile2']['name'];

$file3 = $_FILES['upfile3']['tmp_name'];
$filename3 = $_FILES['upfile3']['name'];

if($file1 != NULL){
  $f1 = $user_id._.$date1._.$date2._.$filename1;
  $folder = "../../up/upfile/".$f1;
  move_uploaded_file($file1 , $folder);
}

if($file2 != NULL){
  $f2 = $user_id._.$date1._.$date2._.$filename2;
  $folder = "../../up/upfile/".$f2;
  move_uploaded_file($file2 , $folder);
}

if($file3 != NULL){
  $f3 = $user_id._.$date1._.$date2._.$filename3;
  $folder = "../../up/upfile/".$f3;
  move_uploaded_file($file3 , $folder);
}

if($title==NULL||$content==NULL)
{
        echo "<script>alert('빈칸을 모두 입력해주세요!.');";
        echo "window.location.replace('../newboard.php');</script>";
        exit();
}
else if($sort == '자유')
{
  if($password == NULL)
{
        echo "<script>alert('게시물 비밀번호를 입력하세요!.');";
        echo "window.location.replace('../newboard.php');</script>";
}
else if($sub_sort == 'NULL')
  $sub_sort = '전체';
}

$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');

if(mysqli_connect_errno($mysqli)){
            echo "연결실패";
    }else{
            echo "성공";
    }

if($sub_sort == 'NULL')
{
    $check = "INSERT INTO board VALUES" . "(DEFAULT,'$title','$sort', '$hits','$user_id','$user_name',now(),'$content','$filename1','$filename2','$filename3','$date1','$date2', DEFAULT)";
    echo "<script>window.location.replace('../board.php');</script>";
    $result = $mysqli->query($check);
}
else
{
    $check = "INSERT INTO ann_board VALUES" . "(DEFAULT,'$title','$sub_sort','$hits',now(),'$date2','$date1','$content','$password')";//added password
    echo "<script>window.location.replace('../board.php?sort=free');</script>";
    $result = $mysqli->query($check);
}

exit;

?>
