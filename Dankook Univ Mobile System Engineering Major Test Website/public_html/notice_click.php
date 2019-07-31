<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
  <link href="css/notice_click_css.css" type="text/css" rel="stylesheet">
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
      <a target="_self" href="notice_click.php">MEMBERS</a>
    </div>
  </nav>

    <div id="login_box">
      <?php
    	if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) { ?>
        <form method="post" action="php/login_ok.php">
        <div id="login_input">
          아이디 <input type="text" name="user_id"/>
          비밀번호 <input type="password" name="user_pw"/>
          <input type="submit" value="로그인" class="login_btn"/>
          <a href="php/signup.php" target="_self" id="register">회원가입</a>
        </div>
      </form>
      <?php } else {
          $user_id = $_SESSION['user_id'];
          $user_name = $_SESSION['user_name'];
          echo "<p><strong>$user_name </strong>님~~ "; ?>
            <a href="changepw.php" target="_self" id="register">비밀번호변경</a>
            <a href="php/logout.php" id="forgot">로그아웃</a><?php
      }?>
    </div>
    <div class="counter">
      <?php include "count.php"; ?>
    </div>


  <aside id="left">

    <div class="search_main">
      <form name="frmMainSearch" action onsubmit="return false" autocomplete="off">
        <input title="홈페이지 검색어 입력" type="text" class="inp" style="ime-mode:active" placeholder="검색어 입력">
        <button type="button" name="search" class="btn"></button>
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
        MSE Notice
      </div>
      <div class="small_small_title">
        | &nbsp;공지사항
      </div>

      <div class="main-area" style="overflow: visible">

      <?php

      $mysqli = mysqli_connect('localhost','junmo14','mse','MSE');
      $notice_num = $_GET['id'];

      $check = "SELECT * FROM notice WHERE number = '$notice_num'";
      $result = $mysqli->query($check);

      if(!result){
        echo " fail";
      }else{
      	$notice = $result->fetch_array(MYSQLI_BOTH);
        $id = $notice['number'];
        //echo $id;
      }


      $add_hits = "UPDATE notice SET hits = hits+1 WHERE number = '$notice_num'";
    	$hites_result = $mysqli->query($add_hits);?>


        <article class="notice" style="overflow: visible">
          <div class="notice_title">
            <?php
            echo $notice['title'];?>
          </div>
          <div class="notice_info">
            <div class="notice_info_writer">
              <div class="notice_writer_pic">
                <a href="notice_click.php" class="notice_info_writer.notice_writer_pic">
                  <span class="blind">인</span>
                </a>
              </div>
              <div class="writer">
              <?php
              echo $notice['writer'];?>
              </div>
            </div>
            <div class="notice_info_date">
              <div class="year">
              <?php
              echo $notice['date_year'];?>
              </div>
              <div class="time">
              <?php
              echo $notice['date_hour'];?>
              </div>
            </div>
            <div class="notice_info_hits">
              <div class="notice_info_hits_pic">
                <a href="notice_click.php" class="notice_info_hits.notice_info_hits_pic">
                  <span class="blind">눈</span>
                </a>
              </div>
              <?php
              echo $notice['hits'];?>
            </div>
          </div>
          <div class="notice_attachment">
          <div class="notice_attachment_pic">
            <a href="notice_click.php" class="notice_attachment.notice_attachment_pic">
              <span class="blind">클</span>
            </a>
          </div>
          첨부파일
          <ul>
            <?php
            $n_file1 = $notice['file1'];
            $n_file1_cut = substr($n_file1, 29);

            $n_file2 = $notice['file2'];
            $n_file2_cut = substr($n_file2, 29);

            $n_file3 = $notice['file3'];
            $n_file3_cut = substr($n_file3, 29);
            ?>

            <?php
            if($n_file1 != NULL){?>
            <a href = "php/file_download.php?filename=<?php echo $n_file1?>&check=1" target = "_blank"><?php echo $n_file1_cut?></a><br>
            <?php
            }?>

            <?php
            if($n_file2 != NULL){?>
            <a href = "php/file_download.php?filename=<?php echo $n_file2?>&check=1" target = "_blank"><?php echo $n_file2_cut?></a><br>
            <?php
            }?>

            <?php
            if($n_file3 != NULL){?>
            <a href = "php/file_download.php?filename=<?php echo $n_file3?>&check=1" target = "_blank"><?php echo $n_file3_cut?></a><br>
            <?php
            }?>

          </ul>
        </div>
          <div style="overflow: auto;" class="notice_content"><pre><?php
            echo $notice['content'];
            $result->close();?></pre>
          </div>


          <div class="notice_reply">

            <?php
            $check = "SELECT * FROM notice_comment WHERE notice_number = '$id'";
            $result = $mysqli->query($check);
            if(!$result) die($mysqli->error);
          	$rows = $result->num_rows;
            $notice_comment = $result->fetch_array(MYSQLI_BOTH);
            $number = $notice_comment['number'];

          	for($j=0; $j<$rows; ++$j)
          		{
          			$result->data_seek($j);
          			$comment = $result->fetch_array(MYSQLI_BOTH);
          		?>

          		<tr>
                <div class = notice_reply_pic>
                  <a class="notice_attachment.notice_attachment_pic">
                    <span class="blind">클</span>
                </div>
                <div class="notice_reply_name">
          	    <td width="10" ><?php echo $comment['writer']; ?></td>
              </div>
                <div class="notice_reply_time">
              	<td width="10"><?php echo $comment['date']; ?></td><br>
              </div>
              <div class="notice_reply_content">
                <td width="10" height="20"><?php echo $comment['content']; ?></td>
              </div>
              <form action="php/delete_notice_comment.php?id=<?php echo $id?>&number=<?php echo $number?>" method="post">
            			<input type = "submit" value="X" class="reply_delete"/>
            	</form>
              </tr>
          		<?php
          	}
          	$result->close();
          	$mysqli->close();
          ?>
            <div class="notice_reply_input">

              <form class="comment" action="php/write_notice_comment.php?id=<?php echo $id?>" name="comment" method="post">
                <div class="notice_replyy">
                <textarea name="comment" rows="3" cols="95"></textarea>
                <?php
                if($user_id){?>
                  <input class="comment_btn" type ="submit" value="댓글쓰기"/><?php
                }?>
                </div>
              </form>
              </div>
            </div>
          </div>

        </article>

      <div class="back_bottom">
      </div>

      <?php
      if($user_name == $notice['writer']){?>
      <form action="php/delete_notice.php?id=<?php echo $id?>" method="post">
   <input type = "submit" value="게시글 삭제" class="btn"/>
   </form>
   <form action="editnotice.php?id=<?php echo $id?>" method="post">
     <input type = "submit" value="게시글 수정" class="btn"/>
</form><?php
}?>
      <div class="notice_list">
        <a target="_self" href="notice.php">목록</a>
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
