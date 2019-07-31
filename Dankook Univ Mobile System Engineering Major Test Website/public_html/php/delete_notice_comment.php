<?php
session_start();
$user_name = $_SESSION['user_name'];
$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');
$id = $_GET['id'];
$number = $_GET['number'];



$query_name = "SELECT writer from notice_comment where notice_number = '$id' and number = '$number'";
$select_name = $mysqli->query($query_name);
$check_name = $select_name->fetch_array(MYSQLI_ASSOC);


if($select_name)
{
	if($user_name != $check_name['writer'])
	{
					echo $user_name;
					echo $check_name['writer'];
	        echo "<script>alert('권한이 없습니다!!.');";
	        echo "window.location.replace('../board_click.php?id=$id');</script>";
		exit;
		$select_name->close();
		$mysqli->close();
	}
}

$check = "DELETE FROM notice_comment WHERE notice_number = '$id' and writer = '$user_name' and number = '$number'";
$result = $mysqli->query($check);

if(!$result){
	echo " fail";
}else{
	echo "comment delete</br>";
}
echo "<script>window.location.replace('../notice_click.php?id=$id');</script>";
exit;

?>
