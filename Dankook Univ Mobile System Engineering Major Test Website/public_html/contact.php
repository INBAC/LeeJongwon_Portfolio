<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
  <link href="css/contact_css.css" type="text/css" rel="stylesheet">
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
        Contact
      </div>
      <div class="small_small_title">
        | &nbsp;연락처
      </div>
      <div class="top-area">
          <div class="upper-sub-title">
              ○ 전임교원
          </div>
      </div>

      <div class="bottom-area">
        <div class="middle-area">

        <div class = "professor-list">
          <div class ="professor-photo">
              <span class="shin-photo"></span>
          </div>

          <div class = "professor-information">
            <div class = "professor-name">
              <h3>
                <a href="http://cms.dankook.ac.kr/web/cis/-9?p_p_id=DeptInfo_WAR_empInfoportlet&amp;p_p_lifecycle=0&amp;p_p_state=normal&amp;p_p_mode=view&amp;p_p_col_id=column-2&amp;p_p_col_count=1&amp;_DeptInfo_WAR_empInfoportlet_empId=KLcJIAuE2pTNZ%2FVvL3cD4A%3D%3D&amp;_DeptInfo_WAR_empInfoportlet_action=view_message" title="자세히 보기">
                  <strong>신원용</strong>
                </a>
              </h3>
            </div>
            <div class = "professor-detail">
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "tel-photo"> </span>
                </div>
                <h4>
                  <span>   031 - 8005 - 3253</span>
                </h4>
              </div>
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "email-photo"> </span>
                </div>
                  <div class="email-shin">
                  </div>
              </div>
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "home-photo"> </span>
                </div>
                <h4>
                  <a href="http://sites.google.com/site/cnldankook" target="_blank">
                    <span> 신원용교수님 페이지</span>
                  </a>

                </h4>
              </div>
            </div>
          </div>
        </div>
        <div class = "professor-list">

          <div class ="professor-photo">
              <span class="yoo-photo"></span>
          </div>

          <div class = "professor-information">
            <div class = "professor-name">
              <h3>
              <a href="http://cms.dankook.ac.kr/web/cis/-9?p_p_id=DeptInfo_WAR_empInfoportlet&amp;p_p_lifecycle=0&amp;p_p_state=normal&amp;p_p_mode=view&amp;p_p_col_id=column-2&amp;p_p_col_count=1&amp;_DeptInfo_WAR_empInfoportlet_empId=C2R4pnIVotYQ4Zidksd8ug%3D%3D&amp;_DeptInfo_WAR_empInfoportlet_action=view_message" title="자세히 보기">
                  <strong>유시환</strong>
                </a>
              </h3>
            </div>
            <div class = "professor-detail">
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "tel-photo"> </span>
                </div>
                <h4>
                  <span>   031 - 8005 - 3240</span>
                </h4>
              </div>
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "email-photo"> </span>
                </div>
                <div class="email-yoo">
                </div>
              </div>
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "home-photo"> </span>
                </div>
                <h4>
                  <a href="https://sites.google.com/site/dkumobileos/members/seehwanyoo" target="_blank">
                    <span>유시환교수님 페이지</span>
                  </a>
                </h4>
              </div>
            </div>
          </div>
        </div>
        <div class = "professor-list">

          <div class ="professor-photo">
              <span class="jang-photo"></span>
          </div>

          <div class = "professor-information">
            <div class = "professor-name">
              <h3>
              <a href="http://cms.dankook.ac.kr/web/cis/-9?p_p_id=DeptInfo_WAR_empInfoportlet&amp;p_p_lifecycle=0&amp;p_p_state=normal&amp;p_p_mode=view&amp;p_p_col_id=column-2&amp;p_p_col_count=1&amp;_DeptInfo_WAR_empInfoportlet_empId=k7gdwrvMOJi5qKNTb%2FAZYQ%3D%3D&amp;_DeptInfo_WAR_empInfoportlet_action=view_message" title="자세히 보기">
                <strong>장석호</strong>
              </a>
              </h3>
            </div>
            <div class = "professor-detail">
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "tel-photo"> </span>
                </div>
                <h4>
                  <span>   031 - 8005 - 3241</span>
                </h4>
              </div>
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "email-photo"> </span>
                </div>
                <div class="email-jang">
                </div>
              </div>
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "home-photo"> </span>
                </div>
                <h4>
                  <a href="https://sites.google.com/view/wmalab" target="_blank">
                    <span>장석호교수님 페이지</span>
                  </a>
                </h4>
              </div>
            </div>
          </div>
        </div>
        <div class = "professor-list">

          <div class ="professor-photo">
              <span class="choi-photo"></span>
          </div>

          <div class = "professor-information">
            <div class = "professor-name">
              <h3>
              <a href="http://cms.dankook.ac.kr/web/cis/-9?p_p_id=DeptInfo_WAR_empInfoportlet&amp;p_p_lifecycle=0&amp;p_p_state=normal&amp;p_p_mode=view&amp;p_p_col_id=column-2&amp;p_p_col_count=1&amp;_DeptInfo_WAR_empInfoportlet_empId=nmPI%2BHqAKE5ulbErGXFBow%3D%3D&amp;_DeptInfo_WAR_empInfoportlet_action=view_message" title="자세히 보기">
                <strong>최수한</strong>
              </a>
              </h3>
            </div>
            <div class = "professor-detail">
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "tel-photo"> </span>
                </div>
                <h4>
                  <span>   031 - 8005 - 3243</span>
                </h4>
              </div>
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "email-photo"> </span>
                </div>
                <div class="email-choi">
                </div>
              </div>
              <div class = "professor-detail-inform">

              </div>
            </div>
          </div>
        </div>
        <div class = "professor-list">

          <div class ="professor-photo">
              <span class="lee-photo"></span>
          </div>

          <div class = "professor-information">
            <div class = "professor-name">
              <h3>
              <a href="http://cms.dankook.ac.kr/web/cis/-9?p_p_id=DeptInfo_WAR_empInfoportlet&amp;p_p_lifecycle=0&amp;p_p_state=normal&amp;p_p_mode=view&amp;p_p_col_id=column-2&amp;p_p_col_count=1&amp;_DeptInfo_WAR_empInfoportlet_empId=nmPI%2BHqAKE5ulbErGXFBow%3D%3D&amp;_DeptInfo_WAR_empInfoportlet_action=view_message" title="자세히 보기">
                <strong>이현우</strong>
              </a>
              </h3>
            </div>
            <div class = "professor-detail">
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "tel-photo"> </span>
                </div>
                <h4>
                  <span>   031 - 8005 - 3231</span>
                </h4>
              </div>
              <div class = "professor-detail-inform">
                <div class = "mini-photo-frame">
                <span class = "email-photo"> </span>
                </div>
                <div class="email-lee">
                </div>
              </div>
              <div class = "professor-detail-inform">

              </div>
            </div>
          </div>
        </div>
        </div>
        <div class = "bottom-area2">
          <div id="bottom2-sub-title">
            <h4>
              <span>○ 교학행정팀</span>
            </h4>
          </div>

          <div class = "kyohak-information-frame">
            <div class = "kyohak-information">
            </div>
          </div>
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
