
<?php
	
	include_once dirname(dirname(__FILE__)).'/dbconnect.php'; //兩個dirname可以取得上層目錄
	mysqli_set_charset($con, "utf8");
	
	if(isset($_POST["ban_member_id"]) && !empty($_POST["ban_member_id"])){
		
		include_once dirname(dirname(__FILE__)).'/phpfunction.php';
		
		$ban_member_id = $_POST["ban_member_id"];
		$success = false;
		
		$sql_check = mysqli_query($con, "SELECT member_id, ban FROM members WHERE member_id='$ban_member_id' ");
		$row_check_member = mysqli_num_rows($sql_check);
		if($row_check_member>0){
			if(mysqli_query($con, "UPDATE members SET ban = '1'  WHERE member_id='$ban_member_id'")){
				$success = true;
			}
		}
		echo $success;

	}mysqli_close($con);
?>			
