
<?php
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id']) &&
	isset($_POST["thread_id"]) && !empty($_POST["thread_id"]) &&
	isset($_POST["comment_uploadtype"]) && !empty($_POST["comment_uploadtype"])){
			
		header("Content-Type:text/html; charset=utf-8");
		include_once (dirname(__FILE__).'/phpfunction.php');
		mysqli_set_charset($con, "utf8");
		
		$loggedin_check = true;
		$member_id = $_SESSION['member_id'];
		$thread_id = $_POST["thread_id"];
		$comment_uploadtype = $_POST["comment_uploadtype"];
		$comment_imgtype = $_POST["comment_imgtype"];
		$upload_imgtype = "";
		$date = get_nowdatetime();
		$ip_addr = get_client_ip();
		$image_path = "";
		$image_savepath = "comment-photo/";
		$image_success = true;
		
		$comment = "";
		if(isset($_POST["comment"]) && !empty($_POST["comment"])){
			$comment = $_POST['comment'];
			$regex = "/^\@([\x{4e00}-\x{9fa5}\w- ]+)$/u";
			if (preg_match_all($regex, $comment, $username_arr)) {
				//$username_arr 存放的是條件式塞選出來的所有字串之陣列，規格是$username_arr[0][x]=@會員暱稱     $username_arr[1][x]=會員暱稱
				for($i=0 ; $i<count($username_arr[1]) ; $i++){
					$at_username = $username_arr[1][$i];
					$sql_username_exist = mysqli_query($con, "SELECT member_id FROM member_profile WHERE BINARY username='$at_username' ");
					$rows_username_exist = mysqli_num_rows($sql_username_exist);
					if($rows_username_exist>0){
						//echo $member_id;
						$row_at_username_member_id = mysqli_fetch_assoc($sql_username_exist);
						$replace_member_id = $row_at_username_member_id['member_id'];
						$comment = preg_replace('/\@'.$at_username.'/', "@$replace_member_id", $comment);
					}
				}
			}
			$comment = mysqli_real_escape_string($con, $comment);
		}

		if($comment_uploadtype === 'file'){
			if(isset($_FILES['comment_uploadimg']['tmp_name']) && !empty($_FILES['comment_uploadimg']['tmp_name'])){
				if($_FILES['comment_uploadimg']['error']==0){
					$comment_uploadimg = $_FILES['comment_uploadimg']['tmp_name'];
					if($comment_imgtype==="image/gif"){
						if($image_path = gif_to_gfycat($comment_uploadimg , $comment_uploadtype)){
							$image_path = addslashes($image_path);
							$image_success = true;
							$upload_imgtype = "GIF";
						}
						else{
							$image_success = false;
						}
					}
					else if($image_path = thread_resize_crop_image($comment_uploadimg ,$image_savepath ,320 )){
						$image_path = addslashes($image_path);
						$image_success = true;
						$upload_imgtype = "JPG";
					}
				}
			}
		}
		else if($comment_uploadtype === 'url'){
			$comment_uploadimg = $_POST["comment_uploadimg"];
			if($comment_imgtype==="image/gif"){
				if($image_path = gif_to_gfycat($comment_uploadimg , $comment_uploadtype)){
					$image_path = addslashes($image_path);
					$image_success = true;
					$upload_imgtype = "GIF";
				}
				else{
					$image_success = false;
				}
			}
			else if($image_path = thread_resize_crop_image($comment_uploadimg ,$image_savepath ,320 )){
				$image_path = addslashes($image_path);
				$image_success = true;
				$upload_imgtype = "JPG";
			}
		}
	if($image_success===true){
		$reply_comments_id_check = true;
		while($reply_comments_id_check){
			$reply_comments_id = create_new_reply_comments_id();
			$sql_reply_comments_id_check = mysqli_query($con, "SELECT reply_comments_id FROM reply_comments WHERE BINARY reply_comments_id='$reply_comments_id' ");
			$rows_reply_comments_id = mysqli_num_rows($sql_reply_comments_id_check);
			if($rows_reply_comments_id<1){
				$reply_comments_id_check = false;
			}
		}

		$sql_sendcomment = "INSERT INTO reply_comments(reply_comments_id ,thread_id , member_id, comments, date , image_type , image , who_like , ip_address) VALUES('$reply_comments_id' , '$thread_id', '$member_id', '$comment' ,'$date' , '$upload_imgtype' , '$image_path', ' ' ,'$ip_addr')";

		if(mysqli_query($con, $sql_sendcomment)){
			$sql_comments = mysqli_query($con, "SELECT * FROM reply_comments WHERE reply_comments_id='$reply_comments_id' ");
			$row = mysqli_fetch_array($sql_comments);
			$reply_comments_id = $row['reply_comments_id'];
			$divreply_reply_com_id = $row['reply_comments_id'];
			$nft_reply_rcid = $row['reply_comments_id'];
			$thread_id = $row['thread_id'];
			$comments_member_id = $row['member_id'];
			$comments_wholike = $row['who_like'];
			$comments_likecount = $row['comments_like'];
			$comments_nft_onoff = $row['nft_onoff'];
			$comments_image_type = $row['image_type'];
			$comments_image = $row['image']; //GIF直接用這個
			//$comments_image_path = "http://".$_SERVER['SERVER_NAME']."/llamafun/".$comments_image; //不是GIF的話
			$comments_image_path = $comments_image;
			$member_post_url = "/member-post.php?post_member_id=".$comments_member_id;
			
			$comments = $row['comments'];
			$regex = "/\@([\w]+)/";
			$comments = htmlspecialchars($comments);//輸入HTML語法會被直接顯示出來不會有效果
			if (preg_match_all($regex, $comments, $member_id_arr)) {
				//$member_id_arr 存放的是條件式塞選出來的所有字串之陣列，規格是$member_id_arr[0][x]=@會員ID     $member_id_arr[1][x]=會員ID
				for($i=0 ; $i<count($member_id_arr[1]) ; $i++){
					$member_id = $member_id_arr[1][$i];
					$sql_member_id_exist = mysqli_query($con, "SELECT member_id FROM members WHERE BINARY member_id='$member_id' ");
					$rows_member_id_exist = mysqli_num_rows($sql_member_id_exist);
					if($rows_member_id_exist>0){
						//echo $member_id;
						$sql_at_member_id = mysqli_query($con, "SELECT username FROM member_profile WHERE member_id='$member_id'");
						$row_at_member_id_info = mysqli_fetch_assoc($sql_at_member_id);
						$at_username = $row_at_member_id_info['username'];
						$comments = preg_replace('/\@'.$member_id.'/', '<a href="/member-post.php?post_member_id='.$member_id.'" target=_blank>@'.$at_username.'</a>', $comments);
					}
				}
			}
			
			$sql_nft_info = mysqli_query($con, "SELECT member_id,nft_onoff FROM threads WHERE thread_id='$thread_id'");
			$row_nft_info = mysqli_fetch_array($sql_nft_info);
			$thread_member_id = $row_nft_info['member_id'];
			$nft_onoff = $row_nft_info['nft_onoff'];
			
			if($comments_member_id != $thread_member_id){ //如果不是自己的文章才新增訊息到DB的通知table
				if($nft_onoff == "1"){
					$nft_id_check = true;
					while($nft_id_check){
						$nft_id = create_new_nftid();
						$sql_nft_id_check = mysqli_query($con, "SELECT nft_id FROM notification WHERE BINARY nft_id='$nft_id' ");
						$rows_nft_id_check = mysqli_num_rows($sql_nft_id_check);
						if($rows_nft_id_check<1){
							$nft_id_check = false;
						}
					}
					$rcv_member_id = $row_nft_info['member_id'];
					$date = get_nowdatetime();
					mysqli_query($con, "INSERT INTO notification(nft_id, thread_id, reply_comments_id , send_member_id , rcv_member_id , type , date) VALUES('$nft_id', '$thread_id' , '$reply_comments_id'  , '$comments_member_id' , '$rcv_member_id'  , '留言' , '$date'  )");
				}
			}
			
			$sql_member_id_info = mysqli_query($con, "SELECT * FROM member_profile WHERE member_id='$comments_member_id'");
			$row_member_id_info = mysqli_fetch_assoc($sql_member_id_info);
			$total_like = $row_member_id_info['total_like'];
			$userpic = $row_member_id_info['pic'];
			//$userpic_path = "http://".$_SERVER['SERVER_NAME']."/llamafun/".$userpic;
			$userpic_path = $userpic;
			$username = $row_member_id_info['username'];
			
?>				
			<div class="comments-list" id="comments-list_<?php echo $reply_comments_id; ?>">
				<div class="comments-member-id-info">
					<span class="comments-member-id-pic"><?php echo '<a href='.$member_post_url.' target=_blank><img src="'.$userpic_path.'"width="40px" height="40px"/></a>'; ?></span>
					<span class="comments-member-id"><?php echo "<a href=\"$member_post_url\" target=_blank>$username</a>"; ?></span>
					<div class="comments-dropdown-div">
					  <img class="comments-dropdown" src="/image/dropdown_icon.png"/>
					  <div class="dropdown-content" >
						<a id="comment-nft-off_<?php echo $thread_id; ?>" class="comment-nft-onoff" href="javascript: void(0)" >關閉這個文章的通知</a>
						<a id = "comments-del_<?php echo $reply_comments_id; ?>" class="comments-delete" href="javascript: void(0)" >刪除</a>
					  </div>
					</div>
					<?php// if($login_member_id !== $comments_member_id){	?>
								<!--<a id = "comments-report_<?php //echo $thread_id."_".$loggedin_check."_".$comments_member_id."_".$reply_comments_id; ?>" class="thread-com-report" href="javascript: void(0)" >檢舉</a>-->
					<?php//	}	?>
				</div>
			<div class="comments-comments">
				<?php echo nl2br($comments);;
						if($comments_image != null){
							if(isset($comments) && !empty($comments)){
								echo "<br>"; //如果$comments不是空的才空行
							}
							if($comments_image_type==="GIF"){ ////送出後馬上顯示的GIF要讓他autoplay，不然顯示出來還要滾動卷軸才會播放?>
								<video class="comment-gif-video" width="170" autoplay loop muted playsinline >
									<source src="<?php echo $comments_image; ?>" type="video/mp4">Your browser does not support HTML5 video.
								</video>
				<?php		}
							else{?>
								<img class="comment-image" src="<?php echo $comments_image_path; ?>" width="170px" />
				<?php		}
						}
				?>
			</div>	
				<div class = "commentslike" ID = "commentslike_<?php echo $reply_comments_id; ?>">
					<span class="commentslikecount" id="commentslikecount_<?php echo $reply_comments_id; ?>" ><?php echo $comments_likecount."隻 · "?></span>
					<span id = "comments-likebtn_<?php echo $reply_comments_id."_".$comments_member_id."_".$loggedin_check; ?>" class="comments-likebtn comlike_gray "><a  href="javascript:void(0)" >拉馬</a> · </span> 
					<span id = "reply_<?php echo $divreply_reply_com_id."_".$comments_member_id."_".$username."_".$nft_reply_rcid; ?>" class="replybtn" ><a href="javascript:void(0)">回覆</a></span>	
					<!--<span><a id = "comments-del_<?php //echo $reply_comments_id; ?>" class="comments-delete" href="javascript: void(0)" >刪除</a></span>-->
				</div>
				<div class="div-reply" id="div-reply_<?php echo $reply_comments_id; ?>">
				</div>
				<div style="display:none" class="divreply-form" id="divreply-form_<?php echo $divreply_reply_com_id; ?>">
					<span class="replyto" id="replyto_<?php echo $divreply_reply_com_id; ?>"></span>
					<textarea rows="1" maxlength="250" placeholder="留言..." class="textarea-reply" id="textarea-reply_<?php echo $divreply_reply_com_id; ?>" ></textarea>
					<input placeholder="圖片URL" type="text" class="reply-image-url" id="reply-image-url_<?php echo $divreply_reply_com_id."_".$loggedin_check;?>" />
					<span class="reply-upload-submit">
						<a href="javascript: void(0)" class="reply-upload-file" id="reply-upload-file_<?php echo $divreply_reply_com_id;?>">上傳
							<input type="file" accept="image/gif, image/jpeg , image/png"  class="reply-image-file" id="reply-image-file_<?php echo $divreply_reply_com_id."_".$loggedin_check;?>"  autocomplete="off">
						</a>
						<a href="javascript: void(0)" class="reply-upload-cancel" id="reply-upload-cancel_<?php echo $divreply_reply_com_id."_".$loggedin_check;?>" >取消</a>
						<a id = "submit-reply_<?php echo $divreply_reply_com_id."_".$thread_id."_".$loggedin_check; ?>" class="submit-reply" href="javascript: void(0)" >送出</a>
					</span>
					<div class="comment-reply-msg" id="comment-reply-msg_<?php echo $divreply_reply_com_id; ?>"></div>
					<div class="send-reply-msg" id="send-reply-msg_<?php echo $divreply_reply_com_id; ?>">傳送中...</div>
				</div>
			</div>
<?php
			}
		}
	}
	else{
		echo false;
	}mysqli_close($con);		
			
?>