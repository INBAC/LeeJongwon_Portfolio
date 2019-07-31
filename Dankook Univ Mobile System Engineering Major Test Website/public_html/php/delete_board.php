<?php
$password = $_POST['delete_password_check'];
session_start();
$user_name = $_SESSION['user_name'];
$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');
$id = $_GET['id'];
$ann = $_GET['ann'];



if($ann != 1)
{
$query_name = "SELECT writer from board where number = '$id'";
$select_name = $mysqli->query($query_name);
$check_name = $select_name->fetch_array(MYSQLI_ASSOC);

if($select_name)
{
	if($user_name != $check_name['writer'])
	{
	        echo "<script>alert('권한이 없습니다!!.');";
	        echo "window.location.replace('../board_click.php?id=$id');</script>";
		exit;
		$select_name->close();
		$mysqli->close();
	}
}

$query_comment = "DELETE FROM board_comment WHERE board_number = '$id'";
$delete_comment = $mysqli->query($query_comment);

if($delete_comment){
	echo "comment delete";
}

$query_board = "DELETE FROM board WHERE number = '$id' and writer = '$user_name'";
$delete_board = $mysqli->query($query_board);

if($delete_board){
	echo "board delete</br>";
}

echo "<script>window.location.replace('../board.php?id=$id');</script>";
exit;

$delete_comment->close();
$delete_board->close();
$mysqli->close();
}

else
{
		$query_name = "SELECT password from ann_board where number = '$id'";
		$select_name = $mysqli->query($query_name);
		$check_name = $select_name->fetch_array(MYSQLI_ASSOC);
		if($select_name)
			{
				if($password != $check_name['password'])
				{
				        echo "<script>alert('익명게시판 비밀번호가 틀렸습니다!!.');";
				        echo "window.location.replace('../board_click.php?id=$id&ann=1');</script>";
					exit;
					$select_name->close();
					$mysqli->close();
				}
			}
			$query_comment = "DELETE FROM ann_board_comment WHERE board_number = '$id'";
			$delete_comment = $mysqli->query($query_comment);

			if($delete_comment){
				echo "comment delete";
			}

			$query_board = "DELETE FROM ann_board WHERE number = '$id' and password = '$password'";
			$delete_board = $mysqli->query($query_board);

			if($delete_board){
				echo "board delete</br>";
			}

			echo "<script>window.location.replace('../board.php?id=$id&ann=1');</script>";
			exit;

			$delete_comment->close();
			$delete_board->close();
			$mysqli->close();
}
?>
