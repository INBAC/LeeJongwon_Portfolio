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

  <?php
  $user_id = $_SESSION['user_id'];
  $user_name = $_SESSION['user_name'];
  $id = $_GET['id'];
  $ann = $_GET['ann'];
  $password_check = $_POST['edit_password_check'];

  $mysqli = mysqli_connect('localhost','junmo14','mse','MSE');

  if(mysqli_connect_errno($mysqli)){
              echo "연결실패";
  }else{
              echo "성공";
  }

  if($ann != 1)
  {
    $check_1 = "SELECT * FROM board where number = '$id'";
    $result_1 = $mysqli->query($check_1);
    $board = $result_1->fetch_array(MYSQLI_BOTH);

    $title = $board['title'];
    $content = $board['content'];
    $sort = $board['sort'];
    $sub_sort = NULL;
    $password = NULL;
    $showSubSort = "none;";
    $showFileAttachment = "block;";
  }
  else
  {
    $check_1 = "SELECT * FROM ann_board where number = '$id'";
    $result_1 = $mysqli->query($check_1);
    $board = $result_1->fetch_array(MYSQLI_BOTH);

    $title = $board['title'];
    $content = $board['content'];
    $sort = "자유";
    $sub_sort = $board['sub_sort'];
    $password = $board['password'];
    $showSubSort = "block;";
    $showFileAttachment = "none;";
    if($password != $password_check)
    {
            echo "<script>alert('익명게시물 비밀번호가 틀렸습니다!.');";
            echo "window.location.replace('./board_click.php?id=$id&ann=$ann');</script>";
            exit();
    }
  }

  $file1 = $board['file1'];
  $file2 = $board['file2'];
  $file3 = $board['file3'];


  ?>

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

      <form action = "php/edit_board.php?id=<?php echo $id?>&ann=<?php echo $ann?>" method="post" enctype = "multipart/form-data">

      <div class="select-area">
        <div class="board-select">
            게시판
        </div>
        <div class="board-choice">
          <select name="board_type" class="board_type" onChange="showSub(this.options[this.selectedIndex].value);">
													<option value="전체">게시판을 선택해주세요</option>
													<option value="스터디"<?php
                            if($sort == "스터디"){?>
                              selected=""<?php
                            }?>>스터디</option>
													<option value="수업"<?php
                            if($sort == "수업"){?>
                              selected=""<?php
                            }?>>수업</option>
                          <option value="건의사항"<?php
                            if($sort == "건의사항"){?>
                              selected=""<?php
                            }?>>건의사항</option>
                          <option value="자유"<?php
                            if($sort == "자유"){?>
                              selected=""<?php
                            }?>>자유</option>
													</select>
        </div>


        <div id = "set-board-head" style="display: <?php echo $showSubSort ?>">
          <div class="board-head-select">말머리</div>
          <div class="board-head-choice">
            <select name="head_type" class="head_type">
              <option value = "NULL">말머리를 선택해주세요</option>
              <option value="전체"<?php
                if($sub_sort == "전체"){?>
                  selected=""<?php
                }?>>전체</option>
              <option value="연애"<?php
                if($sub_sort == "연애"){?>
                  selected=""<?php
                }?>>연애</option>
              <option value="잡담"<?php
                if($sub_sort == "잡담"){?>
                  selected=""<?php
                }?>>잡담</option>
              <option value="생활"<?php
                if($sub_sort == "생활"){?>
                  selected=""<?php
                }?>>생활</option>
              <option value="취미"<?php
                if($sub_sort == "취미"){?>
                  selected=""<?php
                }?>>취미</option>
              <option value="패션"<?php
                if($sub_sort == "패션"){?>
                  selected=""<?php
                }?>>패션</option>
              <option value="새내기"<?php
                if($sub_sort == "새내기"){?>
                  selected=""<?php
                }?>>새내기</option>
              <option value="기타"<?php
                if($sub_sort == "기타"){?>
                  selected=""<?php
                }?>>기타</option>
            </select>
          </div>
          <div class="board-head-password"><!---->
            게시물 비밀번호 <input type="password" name="board_pw" value = "<?php echo $password ?>"/>
          </div>
          </div>





      </div>

      <div class="board-title-area">
        <div class="make-title" >
          제목
        </div>
        <div class="real-board-title">
          <input class ="title" type="text" name="title" value ="<?php echo $title?>"/>
        </div>
      </div>

      <div id="file-attachment-area" style="display: <?php echo $showFileAttachment ?>">
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
          if($user_id == $board['student_id'] || $ann == 1){?>
            <input class="save_btn" type ="submit" onclick="if(!confirm('글 내용을 수정하시겠습니까?')){return false;}" value="변경"/><?php
          unlink("../up/upfile/".$file1);
          unlink("../up/upfile/".$file2);
          unlink("../up/upfile/".$file3);
          }?>
          <input class="cancel_btn" type ="button" onclick="if(!confirm('취소하시겠습니까? 수정 전의 글로 저장됩니다.')){return false;} history.back();" value="취소"/>
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
