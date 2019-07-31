<!DOCTYPE html>
<?php session_start(); ?>

<html>
<head>
  <link href="css/newnotice_css.css" type="text/css" rel="stylesheet">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script language = "javascript">
  function showSub(selected)
  {
      if(selected == '자유')
      {
        document.getElementById("set-board-head").style.display = "block";
      }
      else
      {
        document.getElementById("set-board-head").style.display = "none";
      }
  }
  </script>
</head>

<body>

  <header>
    <h1>
      <a data-clk="top.logo" href="index.php">
        <span class="mse_logo">MSE</span>
      </a>
    </h1>

  </header>

  <nav>
    <div class="nav_home">
      <a target="_self" href="index.php">HOME</a>
    </div>
    <div class="nav_notice">
      <a target="_self" href="notice.php">NOTICE</a>
    </div>
    <div class="nav_schedule">
      <a target="_self" href="schedule.php">SCHEDULE</a>
    </div>
    <div class="nav_board">
      <a target="_self" href="board.php">BOARD</a>
    </div>
    <div class="nav_contact">
      <a target="_self" href="contact.php">CONTACT</a>
    </div>
    <div class="nav_members">
      <a target="_self" href="members.php">MEMBERS</a>
    </div>
  </nav>

  <div class="login_box">
    <?php
  	if(!isset($_SESSION['user_id'])) { ?>
      <form method="post" action="php/login_ok.php">
      <div class="login_input">
        아이디 <input type="text" name="user_id"/>
        비밀번호 <input type="password" name="user_pw"/>
        <input type="submit" value="로그인" class="login_btn"/>
        <a href="php/signup.php" target="_self" id="register">회원가입</a>
      </div>
    </form>
    <?php } else {
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];
        echo "<p><strong>$user_name</strong> 님~~ "; ?>
          <a href="changepw.php" target="_self" id="register">비밀번호변경</a>
          <a href="php/logout.php" id="forgot">로그아웃</a><?php
    }?>
  </div>

  <div class="counter">
    <?php include "count.php"; ?>
  </div>

  <aside id="left">

    <div class="search_main">
      <form action = "search_result.php" method="get">
        <input title="홈페이지 검색어 입력" type="text" name="search_value" class="inp" style="ime-mode:active" placeholder="검색어 입력">
        <input type="hidden" name="sort" value="search">
        <button type="submit" name="search" class="btn"></button>
      </form>
    </div>

    <div class="laguage">
      <a href="index.html" target="_blank" class="index_kor">한국어</a>
      <a href="home_eng.html" target="_blank" class="index_eng">English</a>
    </div>
  </aside>

  <div class="background">
  </div>
  <div class="background_bottom">
  </div>

  <?php
  $user_id = $_SESSION['user_id'];
  $user_name = $_SESSION['user_name'];
  $id = $_GET['id'];

  $mysqli = mysqli_connect('localhost','junmo14','mse','MSE');

  if(mysqli_connect_errno($mysqli)){
              echo "연결실패";
  }else{
              //echo "성공";
  }

  $check_1 = "SELECT * FROM notice where number = '$id'";
  $result_1 = $mysqli->query($check_1);
  $notice = $result_1->fetch_array(MYSQLI_BOTH);

  $title = $notice['title'];
  //echo $title;
  $content = $notice['content'];
  $sort = $notice['sort'];

  $file1 = $notice['file1'];
  $file2 = $notice['file2'];
  $file3 = $notice['file3'];


  ?>

  <section>
    <div class="main">

      <div class="small_title">
        <div class="blue">
        </div>
        NewNotice
      </div>
      <div class="small_small_title">
        | &nbsp;게시글 쓰기
      </div>

      <div class="main-area">

      <div class="decoration-area">
      </div>

      <form action = "php/edit_notice.php?id=<?php echo $id?>" method="post" enctype = "multipart/form-data">

      <div class="select-area">
        <div class="notice-select">
            게시판
        </div>
        <div class="notice-choice">
          <select name="notice_type" class="notice_type">
													<option value="전체" >공지사항을 선택해주세요</option>
													<option value="학과">학과</option>
													<option value="취업">취업</option>
                          <option value="수업">수업</option>
													</select>
        </div>

      </div>

      <div class="notice-title-area">
        <div class="make-title" >
          제목
        </div>
        <div class="real-notice-title">
          <input class ="title" type="text" name="title" value ="<?php echo $title?>"/>
        </div>
      </div>

      <div class="file-attachment-area">
        <div class="file-title">
          파일첨부
        </div>
        <div class="real-attachment-area" method = "post">
          <input type = 'file' name = 'upfile1'><br>
          <input type = 'file' name = 'upfile2'><br>
          <input type = 'file' name = 'upfile3'>
        </div>
      </div>

      <div class="contents-area">
        <div class="contents-title">
          내용
        </div>
        <div class="real-contents-area">

          <form class="content" name="content" method="get">
            <textarea name="content" rows="20" cols="100" class="content"><?php echo $content ?></textarea>
          </form>

          <?php
          if($user_name == $notice['writer']){?>
          <input class="save_btn" type ="submit" value="변경"/><?php
          unlink("../up/upfile_n/".$file1);
          unlink("../up/upfile_n/".$file2);
          unlink("../up/upfile_n/".$file3);
          }?>
          <input class="cancel_btn" type ="button" onClick="history.back();" value="취소"/>
        </div>
      </div>
      </form>
    </div>

    <div class="back_bottom">
    </div>
    </div>
  </section>
  <?php
    $result_1->close();
    $mysqli->close();
   ?>

  <footer>
    <div class="footer">
      <br />주소 : 경기도 용인시 수지구 죽전로 152, 단국대학교 국제관 201호 (우)16890 <br>
      COPYRIGHT 2019 BY DANKOOK UNIVERSITY, INTERNATIONAL COLLEGE MOBILE SYSTEM ENGINEERING <동아리명>
    </div>
  </footer>
</body>
</html>
