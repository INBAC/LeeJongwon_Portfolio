<?php
session_start();

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$id = $_GET['id'];
$ann = $_GET['ann'];

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

$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');

if(mysqli_connect_errno($mysqli)){
            echo "연결실패";
}else{
            echo "성공";
            echo $user_id;
            echo $user_name;
}

if($ann != 1)
{
$check_1 = "SELECT * FROM board where number = '$id'";
$sort = $_POST['board_type'];
$password = NULL;
}
else
{
  $check_1 = "SELECT * FROM ann_board where number = '$id'";
  $sort = $_POST['head_type'];
  $password = $_POST['board_pw'];
}

$title = $_POST['title'];
$content = $_POST['content'];

$result_1 = $mysqli->query($check_1);
$board = $result_1->fetch_array(MYSQLI_BOTH);

//$sort = $board['sort'];

$date1 = date("Y.m.d");
$date2 = date("H:i:s");

if($ann == 1 && $password==NULL)
{
        echo "<script>alert('익명게시물 비밀번호를 입력해주세요.');";
        echo "window.location.replace('../edit_board.php?id=$id');</script>";
        exit();
}
else if($title==NULL||$content==NULL)
{
        echo "<script>alert('빈칸을 모두 입력해주세요!.');";
        echo "window.location.replace('../edit_board.php?id=$id');</script>";
        exit();
}

if($ann != 1)
  $check = "UPDATE board SET title = '$title' , file1 = '$f1', file2 = '$f2', file3 = '$f3', content = '$content' , sort = '$sort' WHERE number = '$id'";
else
  $check = "UPDATE ann_board SET title = '$title' , content = '$content' , sub_sort = '$sort' , password = '$password' WHERE number = '$id'";
$result = $mysqli->query($check);

echo "<script>window.location.replace('../board.php');</script>";

exit;
$result->close();
$result_1->close();
$mysqli->close();

?>
