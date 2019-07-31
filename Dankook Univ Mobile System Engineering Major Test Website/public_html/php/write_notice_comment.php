<?php
session_start();

$id = $_GET['id'];
$writer = $_SESSION['user_name'];
$content = $_POST['comment'];

echo "id : $id </br>" ;
echo "writer : $writer </br>";
echo "content : $content </br>";

//입력안된것이 있으면 다시 작성페이지로
if($content==NULL)
{
        echo "<script>alert('댓글을 입력해주세요');";
        echo "window.location.replace('../notice_click.php?id=$id');</script>";
        exit();
}

$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');

if(mysqli_connect_errno($mysqli)){
            echo "연결실패";
    }else{
            echo "성공";
    }

$check = "INSERT INTO notice_comment VALUES" . "(DEFAULT,'$id','$writer', now(),'$content')";
$result = $mysqli->query($check);

if(!result){
	echo " fail";
}
echo "<script>window.location.replace('../notice_click.php?id=$id');</script>";
exit;
$mysqli->close();
$result->close();
?>
