 <?php

	include_once (dirname(__FILE__).'/dbconnect.php');
	mysqli_set_charset($con, "utf8");
	include_once (dirname(__FILE__).'/set_session.php');
	
	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){
		$member_id = $_SESSION['member_id'];
		mysqli_query($con, "UPDATE notification SET count_nft = '1'  WHERE rcv_member_id='$member_id' AND count_nft='0' ");
	}
?>