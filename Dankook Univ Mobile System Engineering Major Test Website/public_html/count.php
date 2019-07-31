<?php

$count = file("count.txt");

$count[0] = chop($count[0]);
$count[1] = chop($count[1]);
$count[2] = chop($count[2]);

$date = $count[0];
$countt = (int)$count[1];
$counta = (int)$count[2];

$today = date("Y-m-d");

if(!$_COOKIE["ip"]){

		if($date == $today){
			$countt = $countt + 1;
		}
		else{
			$countt = 1;
			$date = $today;
		}
		$counta = $counta + 1;
		$fp = fopen("count.txt", "w");
		fwrite($fp, "$date\n$countt\n$counta");
		//rewind($fp);
		fclose($fp);
		setcookie("ip", $_SERVER['REMOTE_ADDR'], time()+21600);
}

echo "오늘의 방문객 수 : $countt";
echo "<br>전체 방문객 수 : $counta<br>";
?>
