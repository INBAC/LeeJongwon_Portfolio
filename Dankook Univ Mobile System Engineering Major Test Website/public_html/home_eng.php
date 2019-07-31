<!DOCTYPE html>
<html>
<head>
  <link href="css/index_css.css" type="text/css" rel="stylesheet">
</head>


<body>

  <header>
    <h1>
      <a data-clk="top.logo" href="index.html">
        <span class="mse_logo">MSE</span>
      </a>
    </h1>
  </header>

  <nav>
    <ul>
      <li><a target="iframe1" href="index.php">HOME</a></li>
      <li><a target="iframe1" href="notice.php">NOTICE</a></li>
      <li><a target="iframe1" href="schedule.php">SCHEDULE</a></li>
      <li><a target="iframe1" href="board.php">BOARD</a></li>
      <li><a target="iframe1" href="contact.php">CONTACT</a></li>
      <li><a target="iframe1" href="members.php">MEMBERS</a></li>
    </ul>
  </nav>

  <div id="login_box">
    <form action="#">
      <div id="login_input">
        ID <input type="text"/>
        Password <input type="password"/>
        <input type="submit" value="sign in"/>
        <a href="register.html" target="_blank" id="register">register</a>
        <a href="#" id="forgot">find PW</a>
      </div>
    </form>
  </div>

  <aside id="left">

    <div class="search_main">
      <form name="frmMainSearch" action onsubmit="return false" autocomplete="off">
        <input title="홈페이지 검색어 입력" type="text" class="inp"style="ime-mode:active">
        <button type="button" name="search" class="btn">search</button>
      </form>
    </div>

    <a href="index.php" target="_blank" id="index_kor">한국어</a>
    <a href="home_eng.php" target="_blank" id="home_eng">English</a>
  </aside>

  <section>
    <div class="main">

      <div class="top-area">
          <article id="photo">
            Photo
          </article>
      </div>

      <div class="bottom-area">
        <div class="notice">
          <article id="notice">
            Notice
          </article>
        </div>
        <div class="board">
          <article id="schedule">
            Schedule
          </article>
        </div>
      </div>

    </div>
  </section>

  <footer>
    <div class="footer">
      <br />Addres : Kyunggido Yonginsi Sugikoo Jookjeonro 152, Dankook University International Hall 201 (post)16890 <br>
      COPYRIGHT 2019 BY DANKOOK UNIVERSITY, INTERNATIONAL COLLEGE MOBILE SYSTEM ENGINEERING <동아리명>
    </div>
  </footer>
</body>
</html>
