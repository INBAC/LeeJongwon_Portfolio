<!DOCTYPE html>

<html>
<head>
        <title> 비밀번호변경</title>
        <meta charset = "utf-8">
</head>
<body>


	<form action = "changepw_ok.php" method="post">
		<div>
                        <label for="pw"> 기존 비밀번호 </label>
                        <input type="text" name="pw"/>
                </div>
                <div>
                        <label for="npw"> 새 비밀번호 </label>
                        <input type="text" name="npw"/>
                </div>
                 <div>
                        <label for="nnpw"> 새 비밀번호 확인 </label>
                        <input type="text" name="nnpw"/>
                </div>
                        <p><input type="submit" value="비밀번호 변경" />
                </div>
        </form>
</body>
</html>
