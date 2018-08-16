
<?php
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	mysqli_set_charset($con, "utf8");
	
	if(isset($_POST["thread_id"]) && !empty($_POST["thread_id"]) &&
		isset($_POST["member_id"]) && !empty($_POST["member_id"]) &&
		isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){

		$member_id = "&".$_SESSION['member_id'];
		$thread_id = $_POST["thread_id"];
		$thread_member_id = $_POST["member_id"];
		
		$sql_wholike = mysqli_query($con, "SELECT who_like FROM threads WHERE thread_id='$thread_id'");
		$row = mysqli_fetch_array($sql_wholike);
		$who_like = $row['who_like'];
		
		if(!preg_match("/$member_id/i", $who_like)){
			if(mysqli_query($con, "UPDATE threads SET thread_like = thread_like+1 , who_like = CONCAT(who_like , '$member_id')  WHERE thread_id='$thread_id' ")){
				mysqli_query($con, "UPDATE member_profile SET board_like = board_like+1 , total_like = total_like+1   WHERE member_id='$thread_member_id' ");
				$sql_likecount = mysqli_query($con, "SELECT thread_like FROM threads WHERE thread_id='$thread_id'");
				$row = mysqli_fetch_array($sql_likecount);
				$likecount = $row['thread_like'];
			
?>			
				<span class="likecount" id="likecount_<?php echo $thread_id; ?>" ><?php echo $likecount."隻" ?></span>
				
<?php			
			}
		}
	}mysqli_close($con);
?>			
