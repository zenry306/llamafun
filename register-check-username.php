 <?php

	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');
	mysqli_set_charset($con, "utf8");

	if(isset($_POST["username"]) && !empty($_POST["username"])){
		
		$username = mysqli_real_escape_string($con, $_POST['username']);

		$sql_username_check = mysqli_query($con, "SELECT username FROM member_profile WHERE username='$username' ");
		$rows_username_check = mysqli_num_rows($sql_username_check);

		$check_exist = true;
		
		if($rows_username_check>0){
			 $check_exist = false;
		}
		else{
			$check_exist = true;
		}
		echo $check_exist;
	}mysqli_close($con);
?>