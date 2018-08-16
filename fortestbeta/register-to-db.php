 <?php
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');
	mysqli_set_charset($con, "utf8");

	if(isset($_POST["username"]) && !empty($_POST["username"]) &&
		isset($_POST["password"]) && !empty($_POST["password"]) &&
		isset($_POST["email"]) && !empty($_POST["email"]) &&
		isset($_POST["recaptcha_reg_callback"]) && !empty($_POST["recaptcha_reg_callback"])){
		
		$secret = "6LekoDEUAAAAAJCS4Mgn1dhBQxozE_C9Y-h8Bt94";
		$response = $_POST["recaptcha_reg_callback"];

		$verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");

		$captcha_success=json_decode($verify);

		if ($captcha_success->success==true) {
			
			$username = mysqli_real_escape_string($con, $_POST['username']);
			$password = mysqli_real_escape_string($con, $_POST['password']);
			$email = mysqli_real_escape_string($con, $_POST['email']);
			
			$sql_username_check = mysqli_query($con, "SELECT username FROM member_profile WHERE username='$username' ");
			$rows_username_check = mysqli_num_rows($sql_username_check);
			
			$sql_email_check = mysqli_query($con, "SELECT email FROM members WHERE email='$email' ");
			$rows_email_check = mysqli_num_rows($sql_email_check);

			$check_exist = true;
			
			if($rows_username_check>0 || $rows_email_check>0){
				$check_exist = false;
				echo $check_exist;
			}
			else{
				$date = get_nowdatetime();
				$ip_addr = get_client_ip();
				$member_id = "L";
				$memberid_check = true;
				while($memberid_check){
					$member_id = $member_id.create_new_memberid();
					$sql_memberid_check = mysqli_query($con, "SELECT member_id FROM members WHERE BINARY member_id='$member_id' ");
					$rows_memberid = mysqli_num_rows($sql_memberid_check);
					if($rows_memberid<1){
						$memberid_check = false;
					}
				}
				
				if(mysqli_query($con, "INSERT INTO members(member_id, password, email, date, ip_address) VALUES('$member_id', '$password', '$email' , '$date' ,'$ip_addr')")){
					
					$sql_loginlog_check = mysqli_query($con, "SELECT * FROM login_log WHERE BINARY member_id='$member_id' ");
					$rows_loginlog_check = mysqli_num_rows($sql_loginlog_check);
					
					if($rows_loginlog_check < 1){
						mysqli_query($con, "INSERT INTO login_log(member_id, date, ip_address ) VALUES('$member_id', '$date' , '$ip_addr')");
					}
					else{
						mysqli_query($con, "UPDATE login_log SET date = '$date', ip_address='$ip_addr' WHERE member_id='$member_id' ");
					}
					
					$sql_profile_check = mysqli_query($con, "SELECT member_id FROM member_profile WHERE BINARY member_id='$member_id' ");
					$rows_profile_check = mysqli_num_rows($sql_profile_check);
					if($rows_profile_check < 1){
						$default_profile_pic = "member-pic/default_profile_pic.png"; 
						//若出現 file_get_contents(llamafun/image/default_profile_pic.png): failed to open stream 可使用http路徑 例如$default_profile_pic = "http://115.165.245.37/llamafun/image/default_profile_pic.png";
						$profile_pic = addslashes($default_profile_pic);
						mysqli_query($con, "INSERT INTO member_profile(member_id, username , pic) VALUES('$member_id', '$username' , '$profile_pic' )");
					}
					
					$_SESSION['member_id']=$member_id;
					echo $check_exist;
				}
			}
			
		}
		else if ($captcha_success->success==false) {
			echo false;
		}
		

	}mysqli_close($con);
?>