<?php
	//mysql database connection
	$host = "localhost";
	$user = "root";
	$pass = "Rm6cj8j9ru";
	$db = "llamadbtest";
	$con = mysqli_connect($host, $user, $pass, $db) or die("啊!!!!資料庫有狀況...GYYYYYYYYYYYYYY！" . mysqli_error($con));
	 mysqli_query($con,"SET CHARACTER SET UTF8");
?>