
<?php
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	mysqli_set_charset($con, "utf8");
	
	if(isset($_POST["reply_com_id"]) && !empty($_POST["reply_com_id"]) &&
		isset($_POST["comments_member_id"]) && !empty($_POST["comments_member_id"]) &&
		isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){

		$member_id = "&".$_SESSION['member_id'];
		$reply_com_id = $_POST["reply_com_id"];
		$comments_member_id = $_POST["comments_member_id"];
		
		$sql_wholike = mysqli_query($con, "SELECT who_like FROM reply_comments WHERE reply_comments_id='$reply_com_id' ");
		$row = mysqli_fetch_array($sql_wholike);
		$who_like = $row['who_like'];
		
		if(preg_match("/$member_id/i", $who_like)){
			if(mysqli_query($con, "UPDATE reply_comments SET comments_like = comments_like-1 , who_like = REPLACE(who_like, '$member_id', '') WHERE reply_comments_id='$reply_com_id' ")){
				mysqli_query($con, "UPDATE member_profile SET board_like = board_like-1 , total_like = total_like-1   WHERE member_id='$comments_member_id' ");
				$sql_likecount = mysqli_query($con, "SELECT comments_like FROM reply_comments WHERE reply_comments_id='$reply_com_id' ");
				$row = mysqli_fetch_array($sql_likecount);
				$comments_likecount = $row['comments_like'];
			
		
?>			
				<span class="commentslikecount" id="commentslikecount_<?php echo $reply_com_id; ?>" ><?php echo $comments_likecount."隻 · " ?></span>
<?php
			}
		}
	}mysqli_close($con);
?>			
