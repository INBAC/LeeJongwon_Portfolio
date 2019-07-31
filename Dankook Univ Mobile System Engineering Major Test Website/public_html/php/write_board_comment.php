<?php
session_start();

$id = $_GET['id'];
$ann = $_GET['ann'];
$ann_pw = $_POST['comment_write_password_check'];
$writer = $_SESSION['user_name'];
$writer_id = $_SESSION['user_id'];
$content = $_POST['comment'];

if($content==NULL)
{
        echo "<script>alert('댓글을 입력해주세요');";
        echo "window.location.replace('../board_click.php?id=$id');</script>";
        exit();
}
if($ann == 1 && $ann_pw == NULL)
{
        echo "<script>alert('익명댓글 비밀번호를 입력해주세요');";
        echo "window.location.replace('../board_click.php?id=$id&ann=1');</script>";
        exit();
}
$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');

if(mysqli_connect_errno($mysqli)){
            echo "연결실패";
    }else{
            echo "성공";
    }
    if($ann == 1)
      $check = "INSERT INTO ann_board_comment VALUES" . "(DEFAULT,'$ann_pw','$id', now(),'$content')";
    else
      $check = "INSERT INTO board_comment VALUES" . "(DEFAULT,'$id','$writer','$writer_id', now(),'$content')";
    $result = $mysqli->query($check);

    if(!result){
    	echo " fail";
    }
    echo "<script>window.location.replace('../board_click.php?id=$id&ann=$ann');</script>";
    exit;
    $mysqli->close();
    $result->close();
    ?>
