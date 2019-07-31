<!DOCTYPE html>
<html>

	<head>
		<title> 회원가입</title>
		<link href="../css/signup_css.css" type="text/css" rel="stylesheet">
		<meta charset = "utf-8">
	</head>

	<body>
		<form action = "./signup_ok.php" method="post">
			<div class="student_id">
				<label for="id"> 학번 </label>
				<input type="text" name="id" class="inp"/>
			</div>
			<div class="password">
				<label for="pw"> PW  </label>
				<input type="text" name="pw" class="inp"/>
			</div>
			<div class="name">
				<label for="name"> 이름 </label>
				<input type="text" name="name" class="inp"/>
			</div>
			<p><input type="submit" value="회원가입" class="btn"/>
		</form>
	</body>
</html>
