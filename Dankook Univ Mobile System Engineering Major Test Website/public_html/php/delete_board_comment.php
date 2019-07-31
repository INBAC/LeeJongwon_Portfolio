<?php
session_start();
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];
$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');
$id = $_GET['id'];
$number = $_GET['number'];
$ann = $_GET['ann'];
$password = $_POST['comment_delete_password_check'];

if($ann != 1)
{
		$query_name = "SELECT * from board_comment where board_number = '$id' and number = '$number'";
		$select_name = $mysqli->query($query_name);
		$check_name = $select_name->fetch_array(MYSQLI_ASSOC);


		$check = "DELETE FROM board_comment WHERE board_number = '$id' and number = '$number'";
		$result = $mysqli->query($check);

		if(!$result){
			echo " fail";
		}else{
			echo "comment delete</br>";
		}
		echo "<script>window.location.replace('../board_click.php?id=$id&ann=$ann');</script>";
		exit;
}
else
{
	$query_name = "SELECT * from ann_board_comment where board_number = '$id' and number = '$number'";
	$select_name = $mysqli->query($query_name);
	$check_name = $select_name->fetch_array(MYSQLI_ASSOC);
	if($password != $check_name['password'])
	{
					echo "<script>alert('익명댓글 비밀번호가 틀렸습니다!!.');";
					echo "window.location.replace('../board_click.php?id=$id&ann=$ann');</script>";
		exit;
		$select_name->close();
		$mysqli->close();
	}

	$check = "DELETE FROM ann_board_comment WHERE board_number = '$id' and number = '$number'";
	$result = $mysqli->query($check);

	if(!$result){
		echo " fail";
	}else{
		echo "comment delete</br>";
	}
	echo "<script>window.location.replace('../board_click.php?id=$id&ann=$ann');</script>";
	exit;
}
?>
