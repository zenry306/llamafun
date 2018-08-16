 <?php

	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');
	mysqli_set_charset($con, "utf8");
	include_once (dirname(__FILE__).'/set_session.php');
	
	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){
		$member_id = $_SESSION['member_id'];

		if(mysqli_query($con, "UPDATE notification SET count_nft = '1'  WHERE rcv_member_id='$member_id' AND count_nft='0' ")){
			$showLimit = 7;
			$nft_date = $_POST["nft_date"];
			$sql_notification_query = "SELECT * FROM notification WHERE rcv_member_id='$member_id' AND date>'$nft_date' ORDER BY date DESC  LIMIT ".$showLimit;
			$sql_notification = mysqli_query($con, $sql_notification_query );
			$Rows_notification = mysqli_num_rows($sql_notification);

			//$row = mysqli_fetch_assoc($sql_comments);
			if($Rows_notification > 0){
				while($row = mysqli_fetch_assoc($sql_notification)){
					$nft_id = $row['nft_id'];
					$thread_id = $row['thread_id'];
					$reply_comments_id = $row['reply_comments_id'];
					$nft_target_reply = $row['nft_target_reply'];
					$send_member_id = $row['send_member_id'];
					$rcv_member_id = $row['rcv_member_id'];
					$type = $row['type'];
					$date = $row['date'];
					$date_split = preg_split("/[-\s:]+/", $date);
					//$keywords = preg_split("/[-\s:]+/", "2017-05-26 19:45:56");
					//print_r($date_split);
					//echo $date_split[1]."月".$date_split[2]."日 ".$date_split[3].":".$date_split[4];
					//echo $keywords[3];
					$date_msg = $date_split[1]."月".$date_split[2]."日 ".$date_split[3].":".$date_split[4];
					$read_nft = $row['read_nft'];
					
					$sql_member_id_info = mysqli_query($con, "SELECT * FROM member_profile WHERE member_id='$send_member_id'"); //搜尋傳送訊息的ID暱稱
					$row_member_id_info = mysqli_fetch_assoc($sql_member_id_info);
					$send_username = $row_member_id_info['username'];
					$send_username_pic = $row_member_id_info['pic'];
					//$userpic_path = "http://".$_SERVER['SERVER_NAME']."/llamafun/".$send_username_pic;
					$userpic_path = $send_username_pic;
					
					$sql_thread_subject = mysqli_query($con, "SELECT subject FROM threads WHERE thread_id='$thread_id'"); //搜尋文章的標題
					$row_thread_subject = mysqli_fetch_assoc($sql_thread_subject);
					$thread_subject = "「".$row_thread_subject['subject']."」";
					
					if($type=="留言"){
						//$nft_msg = $send_username." 在你的文章".$thread_subject."留言　　　　".$date_msg."<br>";
						$nft_msg = '<span class="nft-list-member-id" >'.$send_username.'</span> 在你的文章'.$thread_subject.'中留言';
						$nft_url = "/thread-show.php?thread_id=".$thread_id."&nft_rcid=".$reply_comments_id."#comments";
					}
					else if($type=="回覆"){
						
						$sql_replyto = mysqli_query($con, "SELECT reply_to FROM reply_comments WHERE reply_comments_id='$reply_comments_id'"); //搜尋回覆的留言
						$row_replyto = mysqli_fetch_assoc($sql_replyto);
						$reply_to_rcid = $row_replyto['reply_to'];

						//$nft_msg = '<img src="'.$userpic_path.'"width="40px" height="40px"/>'.$send_username." 回應了你在文章".$thread_subject."的留言　　　　".$date_msg;
						
						$nft_msg = '<span class="nft-list-member-id" >'.$send_username.'</span> 回應了你在文章'.$thread_subject.'的留言';
						$nft_url = "/thread-show.php?thread_id=".$thread_id."&nft_rcid=".$reply_to_rcid."&reply_rcid=".$reply_comments_id."&nft_target_rcid=".$nft_target_reply."#comments";
					}
					else if($type=="檢舉-文章"){
						$report_content = $row['report_content'];
						$report_reason = $row['report_reason'];
						$nft_msg = '<span class="nft-list-member-id" >LlamaFUN：</span> <span class="nft-list-reportmsg" >你的文章「'.$report_content.'」因含有「'.$report_reason.'」經檢舉已刪除！</span>';
						//$userpic_path = "http://".$_SERVER['SERVER_NAME']."/llamafun/image/llama_logo_report.jpg";
						$userpic_path = "/image/llama_logo_report.png";
						$nft_url = "javascript: void(0)";
					}
					else if($type=="檢舉-留言"){
						$report_content = $row['report_content'];
						$report_reason = $row['report_reason'];
						$nft_msg = '<span class="nft-list-member-id" >LlamaFUN：</span> <span class="nft-list-reportmsg" >你在文章'.$thread_subject.'中的留言「'.$report_content.'」因含有「'.$report_reason.'」經檢舉已刪除！</span>';
						//$userpic_path = "http://".$_SERVER['SERVER_NAME']."/llamafun/image/llama_logo_report.jpg";
						$userpic_path = "/image/llama_logo_report.png";
						$nft_url = "javascript: void(0)";
					}
					//if($read_nft=="0"){

?>	
					<a href="<?php echo $nft_url; ?>" >
						<div id="nft-list_<?php echo $nft_id."_".$date."_".$read_nft; ?>" class="nft-list <?php if($read_nft=="0"){ echo "nft-unread"; } //若訊息沒讀過加入此class讓顏色有所區別 ?>">
							<!--<div class="ntf-list-inner">-->
								<img class="nft-list-img" src="<?php echo $userpic_path; ?>"/>
								<div class="nft-list-msg-date">
									<div class="nft-list-msg" id="<?php echo $nft_id; ?>">
										<?php echo $nft_msg;  ?>
									</div>
									<div class="nft-list-date" ><?php echo $date_msg;?></div>
								</div>
							<!--</div>-->
						</div>
					</a>
<?php		
					//}
					//else{
?>

<?php				//}
				}
			}
		}
	/*}
	else{
		echo "logout";
	}*/
	}mysqli_close($con);
?>