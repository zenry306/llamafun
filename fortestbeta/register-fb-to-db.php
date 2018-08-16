 <?php
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');
	mysqli_set_charset($con, "utf8");

	if(isset($_POST["fb_name"]) && !empty($_POST["fb_name"]) && isset($_POST["fb_id"]) && !empty($_POST["fb_id"])){
		
		$fb_name = mysqli_real_escape_string($con, $_POST['fb_name']);
		$fb_name = preg_replace('/\s(?=)/', '', $fb_name);
		$fb_id = "F".$_POST["fb_id"];

		$username_check = true;
		while($username_check){
			$sql_username_check = mysqli_query($con, "SELECT username FROM member_profile WHERE username='$fb_name' ");
			$rows_username_check = mysqli_num_rows($sql_username_check);
			if($rows_username_check==0){
				$username_check = false;
			}
			else{
				$fb_name = $fb_name.rand(10000, 99999);
			}
		}
		
		$sql_memberid_check = mysqli_query($con, "SELECT member_id , ban FROM members WHERE BINARY member_id='$fb_id' ");
		$rows_memberid = mysqli_num_rows($sql_memberid_check);
		if($rows_memberid==0){
			$date = get_nowdatetime();
			$ip_addr = get_client_ip();
			$password = create_fb_pw();
			if(mysqli_query($con, "INSERT INTO members(member_id, member_type , password, date, ip_address) VALUES('$fb_id', 'FB' , '$password', '$date' ,'$ip_addr')")){
				
				$sql_loginlog_check = mysqli_query($con, "SELECT * FROM login_log WHERE BINARY member_id='$fb_id' ");
				$rows_loginlog_check = mysqli_num_rows($sql_loginlog_check);
				
				if($rows_loginlog_check < 1){
					mysqli_query($con, "INSERT INTO login_log(member_id, date, ip_address ) VALUES('$fb_id', '$date' , '$ip_addr')");
				}
				else{
					mysqli_query($con, "UPDATE login_log SET date = '$date', ip_address='$ip_addr' WHERE member_id='$fb_id' ");
				}
				
				$sql_profile_check = mysqli_query($con, "SELECT member_id FROM member_profile WHERE BINARY member_id='$fb_id' ");
				$rows_profile_check = mysqli_num_rows($sql_profile_check);
				if($rows_profile_check < 1){
					$default_profile_pic = "member-pic/default_profile_pic.png"; 
					//若出現 file_get_contents(llamafun/image/default_profile_pic.png): failed to open stream 可使用http路徑 例如$default_profile_pic = "http://115.165.245.37/llamafun/image/default_profile_pic.png";
					$profile_pic = addslashes($default_profile_pic);
					mysqli_query($con, "INSERT INTO member_profile(member_id, username , pic) VALUES('$fb_id', '$fb_name' , '$profile_pic' )");
				}
				
				$_SESSION['member_id']=$fb_id;
				echo "success_reg";
				
			}
		}
		else{
			$row_check_ban = mysqli_fetch_assoc($sql_memberid_check);
			$ban = $row_check_ban['ban'];
			if($ban=='1'){
				echo "banned";
			}
			else{
				$_SESSION['member_id']=$fb_id;
				echo "already_reg";
			}
		}

	}mysqli_close($con);
?>