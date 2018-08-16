
<?php
	include_once dirname(dirname(__FILE__)).'/dbconnect.php'; //兩個dirname可以取得上層目錄
	mysqli_set_charset($con, "utf8");
	
	if(isset($_POST["judge"]) && !empty($_POST["judge"]) &&
		isset($_POST["report_id"]) && !empty($_POST["report_id"])&&
		isset($_POST["report_reason"]) && !empty($_POST["report_reason"])){
			
		include_once dirname(dirname(__FILE__)).'/phpfunction.php';
		
		$judge = $_POST["judge"];
		$report_id = $_POST["report_id"];
		$report_reason = $_POST["report_reason"];
		$success = false;
		
		$sql_report_info = mysqli_query($con, "SELECT * FROM report WHERE report_id='$report_id' ");
		$row = mysqli_fetch_array($sql_report_info);
		$member_id = $row['member_id'];
		//$report_reason = $row['report_reason'];
		$report_member_id = $row['report_member_id'];
		$report_content = $row['report_content'];
		$report_content = preg_replace('/\@([\w]+) /',  '', $report_content);
		
		if($judge==='guilty'){
			mysqli_query($con, "UPDATE report SET report_judge = '$judge' , report_reason ='$report_reason' WHERE report_id='$report_id'");
			//mysqli_query($con, "UPDATE member_profile SET rev_report = rev_report+1  WHERE member_id='$report_member_id' ");
			
			$nft_id_check = true;
			while($nft_id_check){
				$nft_id = create_new_nftid();
				$sql_nft_id_check = mysqli_query($con, "SELECT nft_id FROM notification WHERE BINARY nft_id='$nft_id' ");
				$rows_nft_id_check = mysqli_num_rows($sql_nft_id_check);
				if($rows_nft_id_check<1){
					$nft_id_check = false;
				}
			}
			$thread_id = $row['thread_id'];
			//$rcv_member_id = $row_nft_info['member_id'];
			$date = get_nowdatetime();
			$report_type = $row['type'];

			if($report_type==="檢舉-文章"){
				//echo $report_reason;
				$sql_nft = "INSERT INTO notification(nft_id, thread_id, send_member_id , rcv_member_id , type , report_content , report_reason , date) VALUES('$nft_id', '$thread_id' , 'LlamaFUN' , '$report_member_id'  , '$report_type' , '$report_content' , '$report_reason' , '$date' )";
				//$sql_nft = "INSERT INTO notification(nft_id, thread_id, send_member_id , rcv_member_id , date) VALUES('$nft_id', '$thread_id' , 'LlamaFUN' , '$report_member_id'  ,  '$date' )";
				
				$sql_comment_check = mysqli_query($con, "SELECT reply_comments_id, image_type, image FROM reply_comments WHERE thread_id = '$thread_id'"); //搜尋有沒有回覆以及圖片連結
				$row_comment_check = mysqli_num_rows($sql_comment_check);
				if($row_comment_check>0){
					while($comment_row = mysqli_fetch_assoc($sql_comment_check)){
						$del_reply_comments_id = $comment_row['reply_comments_id'];
						$image_type = $comment_row['image_type'];
						$image_path = $comment_row['image'];
						if($image_type==='JPG'){ //如果是JPG就刪除圖片
							$image_path = dirname(dirname(__FILE__)).'/'.$image_path; //因為是上層目錄
							//echo $reply_image_path;
							unlink($image_path); //刪除圖片
						}
					}
					mysqli_query($con, "DELETE FROM notification WHERE thread_id = '$thread_id' AND NOT type = '檢舉-文章' AND NOT type = '檢舉-留言'"); //刪除通知
					mysqli_query($con, "DELETE FROM reply_comments WHERE thread_id = '$thread_id' "); //刪除所有留言及回覆
				}
				
				$sql_image = mysqli_query($con, "SELECT image_type,image,thumbnail FROM threads WHERE thread_id='$thread_id' "); //搜尋文章的圖片將其刪除
				$row_image = mysqli_fetch_assoc($sql_image);
				$image_type = $row_image['image_type'];
				$image_path = $row_image['image'];
				$thumbnail = $row_image['thumbnail'];
				if($image_type==='JPG'){
					$image_path = dirname(dirname(__FILE__)).'/'.$image_path;
					//echo $image_path;
					unlink($image_path);
					//$image_path = row_thread_info['image'];
				}
				else if($image_type==='GIF'){
					$thumbnail = dirname(dirname(__FILE__)).'/'.$thumbnail; //因為是上層目錄
					unlink($thumbnail);
				}
				mysqli_query($con, "DELETE FROM threads WHERE thread_id='$thread_id' ");//刪除文章
				
				
			}
			else if($report_type==="檢舉-留言"){
				$reply_comments_id = $row['reply_comments_id'];
				$sql_nft = "INSERT INTO notification(nft_id, thread_id, reply_comments_id , send_member_id , rcv_member_id , type , report_content , report_reason , date) VALUES('$nft_id', '$thread_id' , '$reply_comments_id'  , 'LlamaFUN' , '$report_member_id'  , '$report_type' , '$report_content' , '$report_reason' , '$date' )";
				
				$sql_reply_check = mysqli_query($con, "SELECT reply_comments_id, image_type, image FROM reply_comments WHERE reply_to = '$reply_comments_id'"); //搜尋有沒有回覆以及圖片連結
				$row_reply_check = mysqli_num_rows($sql_reply_check);
				if($row_reply_check>0){
					while($reply_row = mysqli_fetch_assoc($sql_reply_check)){
						$del_reply_id = $reply_row['reply_comments_id'];
						$reply_image_type = $reply_row['image_type'];
						$reply_image_path = $reply_row['image'];
						mysqli_query($con, "DELETE FROM notification WHERE reply_comments_id = '$del_reply_id' AND NOT type = '檢舉-文章' AND NOT type = '檢舉-留言' "); //刪除通知
						if($reply_image_type==='JPG'){
							$reply_image_path = dirname(dirname(__FILE__)).'/'.$reply_image_path;
							//echo $reply_image_path;
							unlink($reply_image_path);
						}
					}
					mysqli_query($con, "DELETE FROM reply_comments WHERE reply_to = '$reply_comments_id' ");
				}

				$sql_nft_check = mysqli_query($con, "SELECT reply_comments_id FROM notification WHERE reply_comments_id = '$reply_comments_id' AND NOT type = '檢舉-文章' AND NOT type = '檢舉-留言'");
				$row_nft_check = mysqli_num_rows($sql_nft_check);
				if($row_nft_check>0){
					mysqli_query($con, "DELETE FROM notification WHERE reply_comments_id = '$reply_comments_id' AND NOT type = '檢舉-文章' AND NOT type = '檢舉-留言' ");
				}

				$sql_image = mysqli_query($con, "SELECT image_type,image FROM reply_comments WHERE reply_comments_id = '$reply_comments_id' "); //搜尋留言的圖片將其刪除
				$row_image = mysqli_fetch_assoc($sql_image);
				$image_type = $row_image['image_type'];
				$image_path = $row_image['image'];
				if($image_type==='JPG'){
					$image_path = dirname(dirname(__FILE__)).'/'.$image_path;
					//echo $image_path;
					unlink($image_path);
					//$image_path = row_thread_info['image'];
				}
				mysqli_query($con, "DELETE FROM reply_comments WHERE reply_comments_id = '$reply_comments_id' ");//刪除留言
			}
			mysqli_query($con, $sql_nft); //insert檢舉通知至使用者
			$success = true;
		}
		else if($judge==='notguilty'){
			mysqli_query($con, "UPDATE report SET report_judge = '$judge'  WHERE report_id='$report_id'");
			//mysqli_query($con, "UPDATE member_profile SET send_report = send_report+1  WHERE member_id='$member_id' ");
			$success = true;
		}
		echo $success;

	}mysqli_close($con);
?>			
