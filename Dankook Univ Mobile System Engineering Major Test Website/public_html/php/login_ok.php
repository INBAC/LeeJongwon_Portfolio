<?php
    if ( !isset($_POST['user_id']) || !isset($_POST['user_pw']) ) {
        header("Content-Type: text/html; charset=UTF-8");
        echo "<script>alert('아이디 또는 비밀번호가 빠졌거나 잘못된 접근입니다.');";
        echo "window.location.replace('../index.php');</script>";
        exit;
    }
    $user_id = $_POST['user_id'];
    $raw_pw = $_POST['user_pw'];
  //  $user_name = $_POST['user_name'];

    $conn = new mysqli('localhost', 'hng3412', '748596', 'MSE');

    $query = "SELECT id, pw, name from user where id = '$user_id'";
  //  $query2 = "SELECT name from user where id = '$user_id'";

    $result = $conn->query($query);
  //  $result2 = $conn->query($query2);

    $row = $result->fetch_array(MYSQLI_ASSOC);
    $hash = $row['pw'];
    session_start();
  //  $row2 = $result2->fetch_array(MYSQLI_BOTH);
    if($result)
    {
    //  session_start();
      $_SESSION['user_name'] = $row['name'];
    }
//    $user_pw = password_verify($raw_pw, $row['pw']);

    if($conn->connect_error){
    die ($conn->connect_error);
    }

    if(password_verify($raw_pw, $hash)){
    	//  session_start();
          $_SESSION['user_id'] = $user_id;
          //$_SESSION['user_name'] = $user_name;

          echo "<script>window.location.replace('../index.php');</script>";
          exit;
    }

    else{
      echo "<script>alert('로그인 실패!!.');";
      echo "window.location.replace('../index.php');</script>";
      exit;
  }

    //echo "fail<br>";
?>
