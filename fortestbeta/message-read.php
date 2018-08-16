 <?php

	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');
	mysqli_set_charset($con, "utf8");
	include_once (dirname(__FILE__).'/set_session.php');
	
	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])&&
		isset($_POST["nft_id"]) && !empty($_POST["nft_id"])){
		$member_id = $_SESSION['member_id'];
		$nft_id = $_POST["nft_id"];
		
		mysqli_query($con, "UPDATE notification SET read_nft = '1'  WHERE rcv_member_id='$member_id' AND nft_id='$nft_id' ");

	}mysqli_close($con);
?>