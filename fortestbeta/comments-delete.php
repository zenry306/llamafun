
<?php
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	mysqli_set_charset($con, "utf8");
	
	if(isset($_POST["reply_com_id"]) && !empty($_POST["reply_com_id"]) &&
		isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){

		$member_id = $_SESSION['member_id'];
		$reply_comments_id = $_POST["reply_com_id"];
		$sql_check = mysqli_query($con, "SELECT member_id FROM reply_comments WHERE reply_to = '$reply_comments_id' ");
		$row_check = mysqli_num_rows($sql_check);
		//echo $reply_comments_id."~".$member_id;
		$sql_image = mysqli_query($con, "SELECT image_type,image FROM reply_comments WHERE reply_comments_id = '$reply_comments_id' ");
		$row_image = mysqli_fetch_assoc($sql_image);
		$image_type = $row_image['image_type'];
		$image_path = $row_image['image'];
		if($row_check<1){
			$sql_nft_check = mysqli_query($con, "SELECT reply_comments_id FROM notification WHERE reply_comments_id = '$reply_comments_id' ");
			$row_nft_check = mysqli_num_rows($sql_nft_check);
			if($row_nft_check>0){
				mysqli_query($con, "DELETE FROM notification WHERE reply_comments_id = '$reply_comments_id' AND send_member_id='$member_id' ");
			}
			if(mysqli_query($con, "DELETE FROM reply_comments WHERE reply_comments_id = '$reply_comments_id' AND member_id='$member_id' ")){
				if($image_type==='JPG'){
					unlink($image_path);
					//$image_path = row_thread_info['image'];
				}
				echo true;
			}
		}
		
	}mysqli_close($con);
?>			
