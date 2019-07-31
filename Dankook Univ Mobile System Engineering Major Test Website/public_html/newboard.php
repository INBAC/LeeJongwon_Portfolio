<!DOCTYPE html>
<?php session_start(); ?>

<html>
<head>
  <link href="css/newboard_css.css" type="text/css" rel="stylesheet">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script language = "javascript">
  function showSub(selected)
  {
    if(selected == '자유')
    {
      document.getElementById("set-board-head").style.display = "block";
      document.getElementById("file-attachment-area").style.display = "none";
    }
    else
    {
      document.getElementById("set-board-head").style.display = "none";
      document.getElementById("file-attachment-area").style.display = "block";
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

  <section>
    <div class="main">

      <div class="small_title">
        <div class="blue">
        </div>
        NewBoard
      </div>
      <div class="small_small_title">
        | &nbsp;게시글 쓰기
      </div>

      <div class="main-area">

      <div class="decoration-area">
      </div>

      <form action = "php/write_board.php" method="post" enctype = "multipart/form-data">

      <div class="select-area">
        <div class="board-select">
            게시판
        </div>
        <div class="board-choice">
          <select name="board_type" class="board_type" onChange="showSub(this.options[this.selectedIndex].value);">
													<option value="전체" >게시판을 선택해주세요</option>
													<option value="스터디">스터디</option>
													<option value="수업">수업</option>
                          <option value="건의사항">건의사항</option>
                          <option value="자유">익명</option>
													</select>
        </div>


        <div id = "set-board-head" style="display: none;">
          <div class="board-head-select">말머리</div>
          <div class="board-head-choice">
            <select name="head_type" class="head_type">
              <option value = "NULL">말머리를 선택해주세요</option>
              <option value="전체">전체</option>
              <option value="연애">연애</option>
              <option value="잡담">잡담</option>
              <option value="생활">생활</option>
              <option value="취미">취미</option>
              <option value="패션">패션</option>
              <option value="새내기">새내기</option>
              <option value="기타">기타</option>
            </select>
          </div>
          <div class="board-head-password">게시물 비밀번호<input type="password" name="board_pw"/>
          </div>
          </div>



      </div>

      <div class="board-title-area">
        <div class="make-title" >
          제목
        </div>
        <div class="real-board-title">
          <input class ="title" type="text" name="title"/>
        </div>
      </div>

      <div id="file-attachment-area" style="display: block;">
        <div class="file-title">
          파일첨부
        </div>
        <div class="real-attachment-area">
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

          <form class="content" action="write_board.php" name="content" method="get">
            <textarea name="content" rows="20" cols="100" class="content"></textarea>
          </form>
          <input class="save_btn" type ="submit" onclick="if(!confirm('등록하시겠습니까?')){return false;}" value="등록"/>
          <input class="cancel_btn" type ="button" onclick="if(!confirm('취소하시겠습니까? 작성 중이던 모든 글이 지워집니다.')){return false;} history.back();" value="취소"/>
        </div>
      </div>
      </form>
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
