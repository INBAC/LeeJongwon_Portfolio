<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
  <link href="css/search_result_css.css" type="text/css" rel="stylesheet">
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
        <?php
        $search = $_GET['search_value'];
        ?>
	"<?php echo $search;

	$mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');

	 $string = $search;
          $tok_temp = strtok($string, " ");

        $i = 0;
        for( ;  ; $i++){
                $tok[] = $tok_temp;
           //     echo "단어 = ".$tok[$i]."<br/>";

                if(!($tok_temp = strtok(" "))) break;
        }

        $mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');

        $check = "SELECT * FROM notice WHERE title LIKE '%$tok[0]%' or writer LIKE '%$tok[0]%'";

        for( $j = 1 ; $i > 0 ; --$i,++$j){
                $check = $check."or title LIKE '%".$tok[$j]."%' or writer LIKE '%".$tok[$j]."%'";
	}


        $result = $mysqli->query($check);
	$rows = $result->num_rows;

	$check_board = "SELECT * FROM board WHERE title LIKE '%$tok[0]%' or writer LIKE '%$tok[0]%'";

        for( $j = 1 ; $i > 0 ; --$i,++$j){
                $check_board = $check_board."or title LIKE '%".$tok[$j]."%' or writer LIKE '%".$tok[$j]."%'";
	}

	$result_board = $mysqli->query($check_board);
	$rows_board = $result_board->num_rows;

	$totalrows = $rows + $rows_board;
	?>"에 대한 검색결과(<?php echo $totalrows;?>건)
      </div>
      <div class="content">


        <div class="notice_result">
          <?php  if($rows != 0){?>
	Notice(<?php echo $rows;?>건)<?php
} ?>
  <?php
  if($rows == 0){
    echo "공지사항에 검색 결과가 없습니다.";
  } ?>
        </div>

        <div class="notice_list">
	  <!-- php로 list 띄우기-->
	<?php
	      for($k=0; $k<$rows; $k++){
          if($rows >= 15){
            $rows = 15;
          }



		$notice = $result->fetch_array(MYSQLI_BOTH);
              $id = $notice['number'];?>
              <tr>
                <td width="70" class="list_num"><?php echo $k+1; ?></td>
                <td width="500" class="list_title"><a href='notice_click.php?id=<?php echo $id?>'><?php echo $notice['title'];?></a></td>
                <td width="120" class="list_writer"><?php echo $notice['writer'];?></td>
                <td width="100" class="list_date"><?php echo $notice['date_year'];?></td>
                <td width="100" class="list_hits"><?php echo $notice['hits']."</br>";?></td>
          		</tr><?php
          }?>
        </div>

        <div id="show_more">
	<a href="notice.php?search_value=<?php echo $search;?>&sort=<?php echo "search"?>&search=" target="_self" class="show_more">더보기</a>
        </div>
        <hr>

        <div class="board_result">
          <?php  if($rows_board != 0){?>
	Board(<?php echo $rows_board;?>건)<?php
} ?>
  <?php
  if($rows_board == 0){
    echo "게시판에 검색 결과가 없습니다.";
  } ?>
        </div>

        <div class="board_list">
	  <!-- php로 list 띄우기-->
	<?php
	      for($k=0; $k<$rows_board; $k++){
          if($rows_board >= 15){
            $rows_board = 15;
          }

                $board = $result_board->fetch_array(MYSQLI_BOTH);
              $id = $board['number'];?>
              <tr>
                <td width="70" class="list_num"><?php echo $k+1; ?></td>
                <td width="500" class="list_title"><a href='board_click.php?id=<?php echo $id?>'><?php echo $board['title'];?></a></td>
                <td width="120" class="list_writer"><?php echo $board['writer'];?></td>
                <td width="100" class="list_date"><?php echo $board['date_year'];?></td>
                <td width="100" class="list_hits"><?php echo $board['hits']."</br>";?></td>
                        </tr><?php
          }?>

        </div>

        <div id="show_more">
	<a href="board.php?search_value=<?php echo $search;?>&sort=<?php echo "search"?>&search=" target="_self" class="show_more">더보기</a>
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
