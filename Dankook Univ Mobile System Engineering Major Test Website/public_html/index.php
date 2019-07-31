<!DOCTYPE html>
<?php session_start(); ?>

<html>
<head>
  <link href="css/index_css.css" type="text/css" rel="stylesheet">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script type="text/javascript" src="js/carousel.js"></script>
  <script src="js/index.js" charset="utf-8"></script>
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
      <a href="index.php" target="_self" class="index_kor">한국어</a>
      <a href="home_eng.php" target="_self" class="index_eng">English</a>
    </div>
  </aside>

  <div class="background">
  </div>
  <div class="background_bottom">
  </div>


  <section>
    <div class="main">

      <div class="top-area">
          <article class="photo">
            <div class="galleryContainer">
                <div class="slideShowContainer">
                    <div onclick="plusSlides(-1)" class="nextPrevBtn leftArrow"><span class="arrow arrowLeft"></span></div>
                    <div onclick="plusSlides(1)" class="nextPrevBtn rightArrow"><span class="arrow arrowRight"></span></div>
                    <div class="captionTextHolder"><p class="captionText slideTextFromTop"></p></div>
                    <div class="imageHolder">
                        <img src="css/image/sample1.jpg">1366X768
                        <p class="captionText">Caption Text-01</p>
                    </div>
                    <div class="imageHolder">
                        <img src="css/image/logo.jpg">
                        <p class="captionText">Caption Text-02</p>
                    </div>
                    <div class="imageHolder">
                        <img src="css/image/search_btnn.jpg">
                        <p class="captionText">Caption Text-04</p>
                    </div>
                </div>
                <div id="dotsContainer"></div>
            </div>
            <script src="js/carousel.js"></script>
          </article>
      </div>

      <div class="bottom-area">
        <div class="notice">
          <article class="notice">
            <div class="noti_title">
              MSE NOTICE
              <a href="notice.php" target="_self" class="button">+더보기</a>
            </div>
            <div class="noti_type">
              <?php
              $notice_sort = $_GET['notice_sort'];
              $board_sort = $_GET['board_sort'];?>
              <ul>
                  <li><a class="noticeTypeList" value="all" target="_parent" href="index.php?noticea_sort=&board_sort=<?php echo $board_sort?>" style="border-right: 1px solid black;">전체</a></li>
                  <li><a class="noticeTypeList" value="mse" target="_top" href="index.php?notice_sort=<?php $notice_sort = 'mse'; echo $notice_sort?>&board_sort=<?php echo $board_sort?>" style="border-right: 1px solid black;">학과공지</a></li>
                  <li><a class="noticeTypeList" value="recruiting" target="_parent" href="index.php?notice_sort=<?php $notice_sort = 'recruiting'; echo $notice_sort?>&board_sort=<?php echo $board_sort?>" style="border-right: 1px solid black;">취업공지</a></li>
                  <li><a class="noticeTypeList" value="class" target="_parent" href="index.php?notice_sort=<?php $notice_sort = 'class'; echo $notice_sort?>&board_sort=<?php echo $board_sort?>">수업공지</a></li>
                </ul>
            </div>
            <div class="noti_main">
              <div class="contents">

                <?php
              	$mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');
                $notice_sort = $_GET['notice_sort'];
                $board_sort = $_GET['board_sort'];

                if($notice_sort == NULL){
              	$check = "SELECT * FROM notice";
                }
                else if($notice_sort == 'mse'){
              	$check = "SELECT * FROM notice WHERE sort = '학과'";
                }
                else if($notice_sort == 'recruiting'){
              	$check = "SELECT * FROM notice WHERE sort = '취업'";
                }
                else if($notice_sort == 'class'){
              	$check = "SELECT * FROM notice WHERE sort = '수업'";
                }
              	$result = $mysqli->query($check);

                if(!$result) die($mysqli->error);
              	$rows = $result->num_rows;
                for($j=$rows; $j>$rows-10; $j--)
              		{
                    if($j == 0)
                      break;
              			$result->data_seek($j-1);
              			$notice = $result->fetch_array(MYSQLI_BOTH);
              			$id = $notice['number'];
              		?>
              		<tr>
              		  <td width="500"><a overflow="hidden" href='notice_click.php?id=<?php echo $id?>' class="notice_border"><?php echo "&nbsp;⋅ [" . $notice['sort'] . "] " . $notice['title'];?></a></br></td>
              		</tr>
              		<?php
              	}
              	$result->close();
              	$mysqli->close();

              ?>
              </div>
            </div>

          </article>
        </div>
        <div class="board">
          <article class="board">

            <div class="board_title">
              MSE BOARD
              <a href="board.php" target="_self" class="button">+더보기</a>
            </div>
            <div class="board_type">
              <ul>
                  <li><a class="boardTypeList" value="all" target="_self" href="index.php?notice_sort=<?php echo $notice_sort?>&board_sort=" style="border-right: 1px solid black;">전체</a></li>
                  <li><a class="boardTypeList" value="study" target="_self" href="index.php?notice_sort=<?php echo $notice_sort?>&board_sort=<?php $board_sort = 'study'; echo $board_sort?>" style="border-right: 1px solid black;">스터디</a></li>
                  <li><a class="boardTypeList" value="class" target="_self" href="index.php?notice_sort=<?php echo $notice_sort?>&board_sort=<?php $board_sort = 'class'; echo $board_sort?>" style="border-right: 1px solid black;">수업</a></li>
                  <li><a class="boardTypeList" value="suggestion" target="_self" href="index.php?notice_sort=<?php echo $notice_sort?>&board_sort=<?php $board_sort = 'suggestion'; echo $board_sort?>" style="border-right: 1px solid black;">건의사항</a></li>
                  <li><a class="boardTypeList" value="free" target="_self" href="index.php?notice_sort=<?php echo $notice_sort?>&board_sort=<?php $board_sort = 'free'; echo $board_sort?>">자유</a></li>
                </ul>
            </div>

            <div class="board_main">
              <div class="contents">

                <?php
              	$mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');
                $board_sort = $_GET['board_sort'];

                if($board_sort == NULL){
              	$check = "SELECT * FROM board";
                }
                else if($board_sort == 'study'){
              	$check = "SELECT * FROM board WHERE sort = '스터디'";
                }
                else if($board_sort == 'class'){
              	$check = "SELECT * FROM board WHERE sort = '수업'";
                }
                else if($board_sort == 'suggestion'){
              	$check = "SELECT * FROM board WHERE sort = '건의사항'";
                }
                else if($board_sort == 'free'){
              	$check = "SELECT * FROM board WHERE sort = '자유'";
                }
              	$result = $mysqli->query($check);


              	if(!$result) die($mysqli->error);
              	$rows = $result->num_rows;
                for($j=$rows; $j>$rows-10; $j--){
                    if($j == 0)
                      break;
              			$result->data_seek($j-1);
              			$board = $result->fetch_array(MYSQLI_BOTH);
              			$id = $board['number'];
              		?>
              		<tr>
              		  <td width="100" overflow="hidden"><a href='board_click.php?id=<?php echo $id?>' class="board_border"><?php echo "&nbsp;⋅ [" . $board['sort'] . "] " . $board['title'];?></a></br></td>
              		</tr>
              		<?php
              	}
              	$result->close();
              	$mysqli->close();

              ?>
              </div>
            </div>

          </article>
        </div>
        <div class="schedule">
          <article class="schedule">

            <div class="sched_title">
              MSE SCHEDULE
              <a href="schedule.php" target="_self" class="button">+더보기</a>
            </div>
            <div class="sched_main">
              <div class="diaryWrap">
                <div class="diarySearch nav">
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

              </div>
            </div>

          </article>
        </div>
      </div>

    </div>
  </section>

  <footer>
    <div class="footer">
      <br />주소 : 경기도 용인시 수지구 죽전로 152, 단국대학교 국제관 201호 (우)16890 <br>
      COPYRIGHT 2019 BY DANKOOK UNIVERSITY, INTERNATIONAL COLLEGE MOBILE SYSTEM ENGINEERING <1D1S>
    </div>
  </footer>
</body>
</html>
