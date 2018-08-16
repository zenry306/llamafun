
<?php
	header("Content-Type:text/html; charset=utf-8");
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');

	mysqli_set_charset($con, "utf8");
	
	if($_POST["edit_type"] === "edit_check_username"){
		if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id']) && 
			isset($_POST["edit_username"]) && !empty($_POST["edit_username"])){
				
			$member_id = $_SESSION['member_id'];
			$edit_username = mysqli_real_escape_string($con, $_POST['edit_username']);
			//$edit_username = $_POST['edit_username'];
			$sql_username_check = mysqli_query($con, "SELECT member_id,username FROM member_profile WHERE username='$edit_username' ");
			$rows_username_check = mysqli_num_rows($sql_username_check);

			$check_exist = true;
			if($rows_username_check>0){
				$rows = mysqli_fetch_assoc($sql_username_check);
				$profile_member_id = $rows['member_id'];
				if($member_id == $profile_member_id){
					$check_exist = true;
				}
				else{
					$check_exist = false;
				}
				
			}
			else{
				//if(mysqli_query($con, "UPDATE member_profile SET username = '$edit_username'   WHERE member_id='$member_id' ")){
					$check_exist = true;
				//}
			}
			echo $check_exist;
		
		}
	}
	
	if($_POST["edit_type"] === "edit_check_email"){
		if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id']) && 
			isset($_POST["edit_email"]) && !empty($_POST["edit_email"])){
				
			$member_id = $_SESSION['member_id'];
			$edit_email = mysqli_real_escape_string($con, $_POST['edit_email']);

			$sql_email_check = mysqli_query($con, "SELECT member_id,email FROM members WHERE email='$edit_email' ");
			$rows_email_check = mysqli_num_rows($sql_email_check);

			$check_exist = true;
			
			if($rows_email_check>0){
				$rows = mysqli_fetch_assoc($sql_email_check);
				$profile_member_id = $rows['member_id'];
				if($member_id == $profile_member_id){
					$check_exist = true;
				}
				else{
					$check_exist = false;
				}
			}
			else{
				$check_exist = true;
			}
			echo $check_exist;
		}
	}

	if($_POST["edit_type"] === "check_pw_original"){
		if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id']) && 
			isset($_POST["pw_original"]) && !empty($_POST["pw_original"])){
		
			$member_id = $_SESSION['member_id'];
			$pw_original = mysqli_real_escape_string($con, $_POST["pw_original"]);

			$sql_pw_check = mysqli_query($con, "SELECT member_id FROM members WHERE BINARY member_id='$member_id' AND  password='$pw_original'");
			$rows_pw_check = mysqli_num_rows($sql_pw_check);

			$check_pw = true;
			if($rows_pw_check<1 ){
				$check_pw = false;
			}
			else{
				$check_pw = true;
			}
			echo $check_pw;
		}
	}
	
	if($_POST["edit_type"] === "submit_edit_profile"){
		if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])&&
		isset($_POST["edit_username"]) && !empty($_POST["edit_username"])&&
		isset($_POST["profile_uploadtype"]) && !empty($_POST["profile_uploadtype"])){
			
			$member_id = $_SESSION['member_id'];
			$profile_uploadtype = $_POST["profile_uploadtype"];
			$edit_email = "";
			if(isset($_POST["edit_email"]) && !empty($_POST["edit_email"])){
				$edit_email = mysqli_real_escape_string($con, $_POST['edit_email']);
			}
			
			$edit_username = mysqli_real_escape_string($con, $_POST['edit_username']);
			
			$date = get_nowdatetime();
			$ip_addr = get_client_ip();
			$sql_edit_username_pic = "UPDATE member_profile SET username = '$edit_username' , editdate = '$date' , ip_address = '$ip_addr' WHERE member_id='$member_id' ";
			$sql_edit_email_pw = "UPDATE members SET email = '$edit_email' , editdate = '$date' , ip_address = '$ip_addr' WHERE member_id='$member_id' ";
			
			if(isset($_POST["pw_new"]) && !empty($_POST["pw_new"])){
				$pw_new = mysqli_real_escape_string($con, $_POST['pw_new']);
				$sql_edit_email_pw = "UPDATE members SET email = '$edit_email' , password = '$pw_new' , editdate = '$date' , ip_address = '$ip_addr' WHERE member_id='$member_id' ";
			}
			
			if($profile_uploadtype=== 'file'){
				if(isset($_FILES['profile_uploadpic']['tmp_name']) && !empty($_FILES['profile_uploadpic']['tmp_name'])){
					if($_FILES['profile_uploadpic']['error']==0){
						//$filename=$_FILES['profile_uploadpic']['name'];
						$profile_uploadpic=$_FILES['profile_uploadpic']['tmp_name'];
						$filetype=$_FILES['profile_uploadpic']['type'];
						//$filesize=$_FILES['profile_uploadpic']['size'];
						$dir = "member-pic/";
						$max_width = 100;
						$max_height = 100;
						if($filetype=="image/gif"||$filetype=="image/jpeg"||$filetype=="image/jpg"||$filetype=="image/png"){
							if($newpic_path = resize_crop_image($max_width, $max_height, $profile_uploadpic , $dir)){
								$newpic_path = addslashes($newpic_path); //SQL Injection defence!
								//if($newpic_path){ unlink($newpic_path); }
								$sql_edit_username_pic = "UPDATE member_profile SET username = '$edit_username' , pic = '$newpic_path' , editdate = '$date' , ip_address = '$ip_addr' WHERE member_id='$member_id' ";
								
								//儲存舊大頭照路徑到變數
								$sql_userpic = mysqli_query($con, "SELECT pic FROM member_profile WHERE BINARY member_id='$member_id'");
								$row = mysqli_fetch_assoc($sql_userpic);
								$userpic_old = $row['pic'];

								/*
								if(mysqli_query($con, "UPDATE member_profile SET pic = '$newpic'  WHERE member_id='$member_id' ")){
									if($newpic_path){ unlink($newpic_path); }
									echo true;
								}*/
							}
						}
					}
				}
			}
			
			else if($profile_uploadtype === 'url'){
				$profile_uploadpic = $_POST["profile_uploadpic"];
				$dir = "member-pic/";
				$max_width = 100;
				$max_height = 100;
				if($newpic_path = resize_crop_image($max_width, $max_height, $profile_uploadpic , $dir)){
					$newpic_path = addslashes($newpic_path); //SQL Injection defence!
					//if($newpic_path){ unlink($newpic_path); }
					$sql_edit_username_pic = "UPDATE member_profile SET username = '$edit_username' , pic = '$newpic_path' , editdate = '$date' , ip_address = '$ip_addr' WHERE member_id='$member_id' ";
					
					//儲存舊大頭照路徑到變數
					$sql_userpic = mysqli_query($con, "SELECT pic FROM member_profile WHERE BINARY member_id='$member_id'");
					$row = mysqli_fetch_assoc($sql_userpic);
					$userpic_old = $row['pic'];

					/*
					if(mysqli_query($con, "UPDATE member_profile SET pic = '$newpic'  WHERE member_id='$member_id' ")){
						if($newpic_path){ unlink($newpic_path); }
						echo true;
					}*/
				}
			}			
			$success = false;
			if(mysqli_query($con, $sql_edit_username_pic) && mysqli_query($con, $sql_edit_email_pw) ){
				$success = true;
				
				if($profile_uploadtype === 'file' || $profile_uploadtype === 'url'){
					if($userpic_old != "member-pic/default_profile_pic.png"){ //若原本照片是預設照片則不刪除
						unlink($userpic_old); //若不是則刪除剛剛儲存的舊檔名照片
					}
				}

			}
			
			echo $success;
		}
	}

	
	
	
	
?>