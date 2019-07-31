<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
  <link href="css/board_css.css" type="text/css" rel="stylesheet">
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
        Board
      </div>
      <div class="small_small_title">
        | &nbsp;게시판
      </div>
      <div class="top_area">
          <div class="notice_type">
              <ul>
                <li><a target="_self" href="board.php">전체</a></li>
                <li><a target="_self" href="board.php?sort=<?php $sort = 'study'; echo $sort?>">스터디</a></li>
                <li><a target="_self" href="board.php?sort=<?php $sort = 'class'; echo $sort?>">수업</a></li>
                <li><a target="_self" href="board.php?sort=<?php $sort = 'suggestion'; echo $sort?>">건의사항</a></li>
                <li><a target="_self" href="board.php?sort=<?php $sort = 'free'; echo $sort?>">익명</a></li>
              </ul>
          </div>
      </div>

      <form action = "board.php" method="get">
          <input type="text" class="notice_search" name="search_value">
          <input type="hidden" name="sort" value="search">
          <button type="submit" name="search" class="notice_search_btn">검색</button>
      </form>
      <?php
      if($user_id){?>
      <a href="newboard.php" class="new_notice">
        <span class="blind">글쓰기</span>
      </a><?php
      }?>
      <?php
      $sort = $_GET['sort'];
      $search = $_GET['search_value'];
       ?>
      <article id="notice">
        <?php
          if($sort == NULL)
          {?>
           <h1>전체 게시판</h1><?php
          }
          else if($sort == 'study')
            {?>
             <h1>스터디 게시판</h1><?php
            }
          else if($sort == 'class')
            {?>
             <h1>수업 게시판</h1><?php
            }
          else if($sort == 'suggestion')
            {?>
             <h1>건의사항</h1><?php
            }
          else if($sort == 'free')
            {?>
             <h1>익명 게시판</h1><?php
           }
           else if($sort == 'search')
             {?>
               <?php
                if($search == NULL)
                {?>
                  <h1>검색어를 입력 해주세요.</h1></h1><?php
                }
                else
                {?>
                  <h1>"<?php echo $search?>"에 대한 검색결과</h1></h1><?php
                }
            }?>



	    <table class="list-table">
	      <thead>
	          <tr>
			           <th width="70" class="table_num">번호</th>
			           <th width="500" class="table_title">제목</th>
	               <th width="120" class="table_writer">글쓴이</th>
	               <th width="100" class="table_date">작성일</th>
			           <th width="100" class="table_hits">조회수</th>
      	    </tr>
	        </thead>

	<?php
	$mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');
  $check = "SELECT * FROM board WHERE pin = TRUE";
  $result = $mysqli->query($check);
	if(!$result) die($mysqli->error);
	$rows = $result->num_rows;
  for($j = 0; $j < $rows; $j++)
  {
    $result->data_seek($j);
    $board = $result->fetch_array(MYSQLI_BOTH);
    $id = $board['number'];
  ?>
  <tr>
          <td width="70" class="pin_list_num">공지</td>
          <td width="500" class="pin_list_title"><a href='board_click.php?id=<?php echo $id?>'><?php echo "[" . $board['sort'] . "] " . $board['title'];?></a></td>
          <td width="120" class="pin_list_writer"><?php echo $board['writer'];?></td>
          <td width="100" class="pin_list_date"><?php echo $board['date_year'];?></td>
          <td width="100" class="pin_list_hits"><?php echo $board['hits'];?></td>
  </tr>
  <?php
  }


  if($sort == NULL){
	$check = "SELECT * FROM board";
  }
  else if($sort == 'study'){
	$check = "SELECT * FROM board WHERE sort = '스터디'";
  }
  else if($sort == 'class'){
	$check = "SELECT * FROM board WHERE sort = '수업'";
  }
  else if($sort == 'suggestion'){
	$check = "SELECT * FROM board WHERE sort = '건의사항'";
  }
  else if($sort == 'free'){
    $check = "SELECT * FROM ann_board";
    $ann = 1;
  }
  else if($sort == 'search'&&$search != NULL){
          $string = $search;
          $tok_temp = strtok($string, " ");

        $i = 0;
        for( ;  ; $i++){
                $tok[] = $tok_temp;
//                echo "단어 = ".$tok[$i]."<br/>";

                if(!($tok_temp = strtok(" "))) break;
        }

        $mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');

        $check = "SELECT * FROM board WHERE title LIKE '%$tok[0]%' or writer LIKE '%$tok[0]%'";

        for( $j = 1 ; $i > 0 ; --$i,++$j){
                $check = $check."or title LIKE '%".$tok[$j]."%' or writer LIKE '%".$tok[$j]."%'";
        }

  }

	$result = $mysqli->query($check);

	if(!$result) die($mysqli->error);
	$rows = $result->num_rows;
  $page_num = $rows;

  if($page_num % 15 == 0)
    $page_num = floor($rows / 15);
  else
    $page_num = floor($rows / 15) + 1;
  $current_page = $_GET['page_number'];
  if($current_page == NULL)
    $current_page = 0;

	for($j=$rows-15*($current_page); $j>$rows-(15*($current_page+1)); $j--)
		{
      if($j == 0)
          break;

			$result->data_seek($j-1);
			$board = $result->fetch_array(MYSQLI_BOTH);
			$id = $board['number'];
		?>
		<tr>
      <td width="70" class="list_num"><?php echo $j; ?></td>
      <td width="500" class="list_title"><a href='board_click.php?id=<?php echo $id?>&&ann=<?php echo $ann?>'><?php
      if($sort == 'free')
        echo "[" . $board['sub_sort'] . "] " . $board['title'];
      else
        echo "[" . $board['sort'] . "] " . $board['title'];
      ?></a></td>
      <td width="120" class="list_writer"><?php if($sort == 'free'){echo 익명;} else{echo $board['writer'];}?></td>
      <td width="100" class="list_date"><?php echo $board['date_year'];?></td>
      <td width="100" class="list_hits"><?php echo $board['hits'];?></td>
		</tr>
		<?php
	}
	$result->close();
	$mysqli->close();

?>
	      </tbody>
	    </table>
      <div class="bottom_paging_box"><?php
      if($current_page != 0){?>
    		<a href="board.php?sort=<?php echo $sort?>&page_number=0&search_value=<?php echo $search?>" class="page_first">처음</a>
        <a href="board.php?sort=<?php echo $sort?>&page_number=<?php echo $current_page -1?>&search_value=<?php echo $search?>" class="page_prev">이전</a><?php
        }
        for($i = 0; $i < $page_num; $i++)
        {
          if($i == $current_page)
          {?>
            <em class="page_cur"><?php echo $i+1?></em><?php
          }
          else
          {?>
            <a href="board.php?sort=<?php echo $sort?>&page_number=<?php echo $i?>&search_value=<?php echo $search?>" class="page_mid"><?php echo $i+1?></a><?php
          }
        }
        if($current_page != $page_num-1){?>
        <a href="board.php?sort=<?php echo $sort?>&page_number=<?php echo $current_page+1?>&search_value=<?php echo $search?>" class="page_next">다음</a>
        <a href="board.php?sort=<?php echo $sort?>&page_number=<?php echo $page_num-1?>&search_value=<?php echo $search?>" class="page_end">끝</a><?php
      }?>
      </div>
      </article>

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
