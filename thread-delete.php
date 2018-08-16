
<?php
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	mysqli_set_charset($con, "utf8");
	
	if(isset($_POST["thread_id"]) && !empty($_POST["thread_id"]) &&
		isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){

		$member_id = $_SESSION['member_id'];
		$thread_id = $_POST["thread_id"];
		$success = false;
		
		$sql_check = mysqli_query($con, "SELECT thread_id,image_type,image,thumbnail FROM threads WHERE thread_id='$thread_id' AND member_id='$member_id' ");
		$row_check = mysqli_num_rows($sql_check);
		$row_thread_image = mysqli_fetch_assoc($sql_check);
		$thread_image_type = $row_thread_image['image_type'];
		$thread_image_path = $row_thread_image['image'];
		$thread_gif_thumbnail = $row_thread_image['thumbnail'];
		if($row_check>0){
			$sql_nft_check = mysqli_query($con, "SELECT reply_comments_id FROM notification WHERE thread_id = '$thread_id' ");
			$row_nft_check = mysqli_num_rows($sql_nft_check);
			if($row_nft_check>0){
				mysqli_query($con, "DELETE FROM notification WHERE thread_id = '$thread_id' AND NOT type = '檢舉-文章' AND NOT type = '檢舉-留言'");
			}

			$sql_comment_check = mysqli_query($con, "SELECT reply_comments_id, image_type, image FROM reply_comments WHERE thread_id = '$thread_id'"); //搜尋有沒有回覆以及圖片連結
			$row_comment_check = mysqli_num_rows($sql_comment_check);
			if($row_comment_check>0){
				while($comment_row = mysqli_fetch_assoc($sql_comment_check)){
					$del_reply_comments_id = $comment_row['reply_comments_id'];
					$image_type = $comment_row['image_type'];
					$image_path = $comment_row['image'];
					if($image_type==='JPG'){ //如果是JPG就刪除圖片
						//echo $reply_image_path;
						unlink($image_path); //刪除圖片
					}
				}
			}
			if(mysqli_query($con, "DELETE FROM reply_comments WHERE thread_id='$thread_id' ")){
				if(mysqli_query($con, "DELETE FROM threads WHERE thread_id='$thread_id' AND member_id='$member_id' ")){
					if($thread_image_type==='JPG'){
						unlink($thread_image_path);
						//$image_path = row_thread_info['image'];
					}
					else if($thread_image_type==='GIF'){
						unlink($thread_gif_thumbnail);
					}
					$success = true;
				}
			}
		}
		echo $success;
	}mysqli_close($con);
?>			
