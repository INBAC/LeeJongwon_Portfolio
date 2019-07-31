<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
  <link href="css/members_css.css" type="text/css" rel="stylesheet">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
      <a href="index.html" target="_self" class="index_kor">한국어</a>
      <a href="home_eng.html" target="_self" class="index_eng">English</a>
    </div>
  </aside>

  <div class="background">
  </div>
  <div class="background_bottom">
  </div>

  <section>
    <div class="main">

      <div class="small_title">
        <div class="blue">
        </div>
        Members
      </div>
      <div class="small_small_title">
        | &nbsp;멤버
      </div>

      <div class="first_members">

        <div class="members_num">
          <h4>First Members</h4>
        </div>

        <div class="members_list">
          <ul style="list-style-type: square";>
            <li>김주호</li>
            <li>권태완</li>
            <li>류성미</li>
            <li>박혜원</li>
            <li>여승현</li>
            <li>이대훈</li>
            <li>이동구</li>
            <li>이종원</li>
            <li>장준모(♛)</li>
            <li>주소정</li>
            <li>하승아</li>
            <li>한남경</li>
          </ul>
        </div>

        <div class="members_pic">
        </div>
      </div>

      <div class="back_bottom">
      </div>
    </div>
  </section>

  <footer>
    <div class="footer">
      <br />주소 : 경기도 용인시 수지구 죽전로 152, 단국대학교 국제관 201호 (우)16890 <br>
      COPYRIGHT 2019 BY DANKOOK UNIVERSITY, INTERNATIONAL COLLEGE MOBILE SYSTEM ENGINEERING <동아리명>
    </div>
  </footer>
</body>
</html>
