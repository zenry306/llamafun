 <?php

	include_once (dirname(__FILE__).'/dbconnect.php');
	mysqli_set_charset($con, "utf8");
	include_once (dirname(__FILE__).'/set_session.php');
	
	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])&&
		isset($_POST["thread_id"]) && !empty($_POST["thread_id"])&&
		isset($_POST["nft_onoff"]) && !empty($_POST["nft_onoff"])){
		$member_id = $_SESSION['member_id'];
		$thread_id = $_POST["thread_id"];
		$nft_onoff = $_POST["nft_onoff"];
		$success = false;
		if($nft_onoff==='comment-nft-off'){
			$sql_nft_onoff = "UPDATE reply_comments SET nft_onoff = '0'  WHERE thread_id='$thread_id' AND member_id='$member_id' ";
		}
		elseif($nft_onoff==='comment-nft-on'){
			$sql_nft_onoff = "UPDATE reply_comments SET nft_onoff = '1'  WHERE thread_id='$thread_id' AND member_id='$member_id' ";
		}
		if(mysqli_query($con, $sql_nft_onoff)){
			$success = true;
		}
		echo $success;
	}mysqli_close($con);
?>