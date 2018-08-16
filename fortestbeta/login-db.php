 <?php
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');
	mysqli_set_charset($con, "utf8");


	if(isset($_POST["email"]) && !empty($_POST["email"]) && 
		isset($_POST["password"]) && !empty($_POST["password"]) && 
		isset($_POST["recaptcha_login_callback"]) && !empty($_POST["recaptcha_login_callback"])){
			
		$secret = "6LekoDEUAAAAAJCS4Mgn1dhBQxozE_C9Y-h8Bt94";
		$response = $_POST["recaptcha_login_callback"];

		$verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");

		$captcha_success=json_decode($verify);

		if ($captcha_success->success==true) {
			$email = mysqli_real_escape_string($con, $_POST['email']);
			$password = mysqli_real_escape_string($con, $_POST['password']);
			
			$sql_login_check = "SELECT *  FROM members WHERE email='$email' AND BINARY password='$password'";
			$result_login_check = mysqli_query($con, $sql_login_check);
			$rows_login_check = mysqli_num_rows($result_login_check);

			$check_login = true;
			
			if($rows_login_check<1 ){
				echo "信箱或密碼錯誤喔！";
				$check_login = false;
				echo $check_login;
			}
			else{

				$sql_get_member_id = mysqli_query($con, "SELECT member_id , ban FROM members WHERE email='$email' ");
				$row_get_member_id = mysqli_fetch_assoc($sql_get_member_id);
				$ban = $row_get_member_id['ban'];
				if($ban=='1'){ //檢查帳號是否被BAN了
					echo "您的帳號因違反規定已被停權！";
					$check_login = false;
					echo $check_login;
				}
				else{
					$check_login = true;
					$date = get_nowdatetime();
					$ip_addr = get_client_ip();
					$member_id = $row_get_member_id['member_id'];
					$sql_loginlog_check = mysqli_query($con, "SELECT * FROM login_log WHERE BINARY member_id='$member_id' ");
					$rows_loginlog_check = mysqli_num_rows($sql_loginlog_check);
					
					if($rows_loginlog_check < 1){
						mysqli_query($con, "INSERT INTO login_log(member_id, date, ip_address ) VALUES('$member_id', '$date' , '$ip_addr')");
					}
					else{
						mysqli_query($con, "UPDATE login_log SET date = '$date', ip_address='$ip_addr' WHERE member_id='$member_id' ");
					}

					echo $check_login;
					$_SESSION['member_id']=$member_id;
				}
			}
		}
		else if ($captcha_success->success==false) {
			echo false;
		}
	}mysqli_close($con);
?>