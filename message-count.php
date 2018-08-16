 <?php

	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');
	mysqli_set_charset($con, "utf8");
	include_once (dirname(__FILE__).'/set_session.php');
	
	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){
		$member_id = $_SESSION['member_id'];
		if($sql_notification = mysqli_query($con, "SELECT count_nft FROM notification WHERE rcv_member_id='$member_id' AND count_nft='0' ")){
			$all_notificationRows = mysqli_num_rows($sql_notification);
			if($all_notificationRows>0){
				echo $all_notificationRows;
			}
			else{
				echo false;
			}
		}
	}
	else{
		echo "logout";
	}
	mysqli_close($con);
?>