<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
  <link href="css/calendar.css" type="text/css" rel="stylesheet">
  <link href="css/schedule_css.css" type="text/css" rel="stylesheet">
  <script type="text/javascript" src="js/calendar.js">

  </script>
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
      <a href="index.html" target="_blank" class="index_kor">한국어</a>
      <a href="home_eng.html" target="_blank" class="index_eng">English</a>
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
        Schedule
      </div>
      <div class="small_small_title">
        | &nbsp;일정
      </div>
      <div class="diaryWrap">
        <div class="diarySearch nav">
           <div class="left">
               <div class="prev btn" onclick="onClickBtn('prev')">&#60;</div>
               <div class="next btn" onclick="onClickBtn('next')">&#62;</div>
           </div>
           <div class="right">
               <select name="year" id="year" onchange="changeOption()">
               </select>
               <select name="month" id="month" onchange="changeOption()">
               </select>
           </div>
       </div>
       <table id="calendar" class="diaryContent">
           <tr id="days">
               <th class="listHead day font-red">일</th>
               <th class="listHead day">월</th>
               <th class="listHead day">화</th>
               <th class="listHead day">수</th>
               <th class="listHead day">목</th>
               <th class="listHead day">금</th>
               <th class="listHead day font-blue">토</th>
           </tr>
           <tr id="dates">
           </tr>
       </table>
       <div class="bottom">
           <button id="register" class="register btn" onclick="location.href='schedule_register.html'">학사일정 등록</button>
       </div>




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
