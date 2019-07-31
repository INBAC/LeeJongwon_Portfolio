<?php

$id=$_POST['id'];

$pw = password_hash($_POST['pw'], PASSWORD_DEFAULT);

//$pw=$_POST['pw'];
$name=$_POST['name'];

if($id==NULL||$pw==NULL||$name==NULL)
{
	echo "<script>alert('빈칸을 모두 입력해주세요!.');";
	echo "window.location.replace('signup.php');</script>";
	exit();
}

$conn = new mysqli('localhost', 'hng3412', '748596', 'MSE');
if($conn->connect_error) die($conn->connect_error);

if(isset($_POST['id']) &&
	isset($_POST['pw']) &&
	isset($_POST['name'])){

	$query = "INSERT INTO user VALUES" . "('$id','$name','$pw')";
	$result = $conn->query($query);

	if(!$result){
      echo "<script>alert('이미 존재하는 아이디 입니다!');";
      echo "window.location.replace('signup.php');</script>";
      exit();
}
}
echo "<script>alert('회원가입 완료~!');";
echo "window.location.replace('../index.php');</script>";
?>
