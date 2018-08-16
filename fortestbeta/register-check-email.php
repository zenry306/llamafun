 <?php

	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');
	mysqli_set_charset($con, "utf8");

	if(isset($_POST["email"]) && !empty($_POST["email"])){
		
		$email = mysqli_real_escape_string($con, $_POST['email']);

		$sql_email_check = mysqli_query($con, "SELECT email FROM members WHERE email='$email' ");
		$rows_email_check = mysqli_num_rows($sql_email_check);

		$check_exist = true;
		
		if($rows_email_check>0){
			 $check_exist = false;
		}
		else{
			$check_exist = true;
		}
		echo $check_exist;
	}mysqli_close($con);
?>