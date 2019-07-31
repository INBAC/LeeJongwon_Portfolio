<?php

//받아온 id를 usertable에서 찾아 해당 비밀번호와 기존 비밀번호가 일치 하지 않으면 다시
//일치하면 usertable에 insert
//
session_start();
$id = $_SESSION['user_id'];
$pw = $_POST['pw'];
$npw = $_POST['npw'];
$nnpw = $_POST['nnpw'];

$conn = new mysqli('localhost', 'hng3412', '748596', 'MSE');
if($conn->connect_error){
        die ($conn->connect_error);
}


if ( !isset($id) || !isset($pw) || !isset($npw) || !isset($nnpw)) {
        echo "<script>alert('빈칸을 모두 입력해주세요.');";
        echo "window.location.replace('index.php');</script>";
        exit;
}

if ( $npw != $nnpw) {
        echo "<script>alert('새 비밀번호를 정확하게 입력해 주세요.');";
        echo "window.location.replace('index.php');</script>";
        exit;
}

$query = "SELECT id, pw from user where id = '$id'";
$result = $conn->query($query);
$row = $result->fetch_array(MYSQLI_ASSOC);
$hash = $row['pw'];

if(!password_verify($pw, $hash)){
	echo "<script>alert('비밀번호가 일치하지 않습니다.');";
        echo "window.location.replace('index.php');</script>";
	exit;
    }

$npw = password_hash($npw, PASSWORD_DEFAULT);
$query = "UPDATE user SET pw = '$npw' where id = '$id'";
$result = $conn->query($query);
echo "<script>alert('변경완료.');";
echo "window.location.replace('index.php');</script>";
exit;

$conn->close();
?>
