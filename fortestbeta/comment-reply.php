
<?php
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');

	if(isset($_POST["reply_com_id"]) && !empty($_POST["reply_com_id"])	&&
		isset($_POST["thread_id"]) && !empty($_POST["thread_id"]) 		&&
		isset($_POST["at_member_id"]) && !empty($_POST["at_member_id"]) &&
		isset($_POST["nft_reply_rcid"]) && !empty($_POST["nft_reply_rcid"])&&
		isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){
			
		include_once (dirname(__FILE__).'/phpfunction.php');
		
		mysqli_set_charset($con, "utf8");

		$loggedin_check = true;
		$member_id = $_SESSION['member_id'];
		$reply_com_id = $_POST["reply_com_id"]; //儲存至資料庫的reply_to 與最底下的回覆按鈕
		$divreply_reply_com_id = $_POST["reply_com_id"];
		$thread_id = $_POST["thread_id"];
		//$comment_reply = "<a href=\"javascript: void(0)\">$com_account</a> ".$_POST['comment_reply'];
		$reply_uploadtype = $_POST["reply_uploadtype"];
		$reply_imgtype = $_POST["reply_imgtype"];
		$upload_imgtype = "";
		$date = get_nowdatetime();
		$ip_addr = get_client_ip();
		$image_path = "";
		$image_savepath = "comment-photo/";
		$image_success = true;
		
		$comment_reply = "";
		if(isset($_POST["comment_reply"]) && !empty($_POST["comment_reply"])){
			$comment_reply = $_POST['comment_reply'];
			$regex = "/^\@([\x{4e00}-\x{9fa5}\w- ]+)$/u";
			if (preg_match_all($regex, $comment_reply, $username_arr)) {
				//$username_arr 存放的是條件式塞選出來的所有字串之陣列，規格是$username_arr[0][x]=@會員暱稱     $username_arr[1][x]=會員暱稱
				for($i=0 ; $i<count($username_arr[1]) ; $i++){
					$at_username = $username_arr[1][$i];
					$sql_username_exist = mysqli_query($con, "SELECT member_id FROM member_profile WHERE BINARY username='$at_username' ");
					$rows_username_exist = mysqli_num_rows($sql_username_exist);
					if($rows_username_exist>0){
						//echo $member_id;
						$row_at_username_member_id = mysqli_fetch_assoc($sql_username_exist);
						$replace_member_id = $row_at_username_member_id['member_id'];
						$comment_reply = preg_replace('/\@'.$at_username.'/', "@$replace_member_id", $comment_reply);
					}
				}
			}
		}
		
		$at_member_id = "@".$_POST['at_member_id'];
		$comment_reply = $at_member_id." ".$comment_reply; //將AT的帳號加入內文最前面
		$comment_reply = mysqli_real_escape_string($con, $comment_reply);
		
		if($reply_uploadtype === 'file'){
			if(isset($_FILES['reply_uploadimg']['tmp_name']) && !empty($_FILES['reply_uploadimg']['tmp_name'])){
				if($_FILES['reply_uploadimg']['error']==0){
					$reply_uploadimg = $_FILES['reply_uploadimg']['tmp_name'];
					if($reply_imgtype==="image/gif"){
						if($image_path = gif_to_gfycat($reply_uploadimg , $reply_uploadtype)){
							$image_path = addslashes($image_path);
							$image_success = true;
							$upload_imgtype = "GIF";
						}
						else{
							$image_success = false;
						}
					}
					else if($image_path = thread_resize_crop_image($reply_uploadimg ,$image_savepath ,320 )){
						$image_path = addslashes($image_path);
						$image_success = true;
						$upload_imgtype = "JPG";
					}
				}
			}
		}
		else if($reply_uploadtype === 'url'){
			$reply_uploadimg = $_POST["reply_uploadimg"];
			if($reply_imgtype==="image/gif"){
				if($image_path = gif_to_gfycat($reply_uploadimg , $reply_uploadtype)){
					$image_path = addslashes($image_path);
					$image_success = true;
					$upload_imgtype = "GIF";
				}
				else{
					$image_success = false;
				}
			}
			else if($image_path = thread_resize_crop_image($reply_uploadimg ,$image_savepath ,320 )){
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

		$sql_reply = "INSERT INTO reply_comments(reply_comments_id ,thread_id , member_id , comments, date, reply_to ,image_type , image ,who_like ,ip_address) VALUES('$reply_comments_id' ,'$thread_id','$member_id', '$comment_reply' ,'$date', '$reply_com_id' , '$upload_imgtype' , '$image_path', ' ' ,'$ip_addr')";
		
		if(mysqli_query($con,$sql_reply)){
			
			$sql_comments = mysqli_query($con, "SELECT * FROM reply_comments WHERE reply_comments_id='$reply_comments_id' ");
			$row = mysqli_fetch_array($sql_comments);		
			$reply_comments_id = $row['reply_comments_id'];
			$comments_member_id = $row['member_id'];
			$comments_wholike = $row['who_like'];
			$comments_likecount = $row['comments_like'];
			$comments_nft_onoff = $row['nft_onoff'];
			$comments_image_type = $row['image_type'];
			$comments_image = $row['image']; //GIF直接用這個
			//$comments_image_path = "http://".$_SERVER['SERVER_NAME']."/llamafun/".$comments_image; //不是GIF的話
			$comments_image_path = $comments_image;
			$member_post_url = "/member-post.php?member_id=".$comments_member_id;
			
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
						$comments = preg_replace('/\@'.$member_id.'/', '<a href="/member-post.php?member_id='.$member_id.'" target=_blank>@'.$at_username.'</a>', $comments);
					}
				}
			}
			
			$nft_reply_rcid = $_POST['nft_reply_rcid'];
			$sql_nft_info = mysqli_query($con, "SELECT member_id, reply_to , nft_onoff FROM reply_comments WHERE reply_comments_id='$nft_reply_rcid'");
			$row_nft_info = mysqli_fetch_array($sql_nft_info);
			$nft_comment_member_id = $row_nft_info['member_id'];
			$nft_onoff = $row_nft_info['nft_onoff'];
			$nft_reply_to = $row_nft_info['reply_to'];
			//echo "111111111111111111111111111111111111111111";
			if($comments_member_id != $nft_comment_member_id ){ //回覆自己的留言或回覆不會新增訊息到DB的通知table
				//echo "22222222222222222222222222222222222";
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
					if(isset($row_nft_info['reply_to']) && !empty($row_nft_info['reply_to'])){
						mysqli_query($con, "INSERT INTO notification(nft_id, thread_id, reply_comments_id ,nft_target_reply , send_member_id , rcv_member_id , type , date) VALUES('$nft_id', '$thread_id' , '$reply_comments_id' , '$nft_reply_rcid' , '$comments_member_id' , '$rcv_member_id'  , '回覆' , '$date'  )");
					}
					else{
						mysqli_query($con, "INSERT INTO notification(nft_id, thread_id, reply_comments_id , send_member_id , rcv_member_id , type , date) VALUES('$nft_id', '$thread_id' , '$reply_comments_id' , '$comments_member_id' , '$rcv_member_id'  , '回覆' , '$date'  )");
					}
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
																																								
			<div class="reply-list" id="reply-list_<?php echo $reply_comments_id; ?>">
				<div class="comments-member-id-info">
					<span class="comments-member-id-pic"><?php echo '<a href='.$member_post_url.' target=_blank><img src="'.$userpic_path.'"width="40px" height="40px"/></a>'; ?></span>
					<span class="comments-member-id"><?php echo "<a href=\"$member_post_url\" target=_blank>$username</a>"; ?></span>
					<div class="comments-dropdown-div">
					  <img class="comments-dropdown" src="/image/dropdown_icon.png"/>
					  <div class="dropdown-content" >
						<a id="comment-nft-off_<?php echo $thread_id; ?>" class="comment-nft-onoff" href="javascript: void(0)" >關閉這個文章的通知</a>
						<a  id = "reply-del_<?php echo $reply_comments_id;; ?>" class="reply-delete" href="javascript: void(0)" >刪除</a>
					  </div>
					</div>
				</div>
				<span class="comments-comments">
					<?php echo nl2br($comments);
							if($comments_image != null){
								if(isset($comments) && !empty($comments)){
									echo "<br>"; //如果$comments不是空的才空行
								}
								if($comments_image_type==="GIF"){ //送出後馬上顯示的GIF要讓他autoplay，不然顯示出來還要滾動卷軸才會播放
					?>
									<video class="comment-gif-video" width="170" autoplay loop muted playsinline >
										<source src="<?php echo $comments_image; ?>" type="video/mp4">Your browser does not support HTML5 video.
									</video>
					<?php		}
								else{?>
									<img class="comment-image"  src="<?php echo $comments_image_path; ?>" width="170px" />
					<?php		}
							}
					?> 
				</span>
				
				<div class = "commentslike reply-commentslike" ID = "commentslike_<?php echo $reply_comments_id; ?>">
					<span class="commentslikecount" id="commentslikecount_<?php echo $reply_comments_id; ?>" ><?php echo $comments_likecount."隻 · "?></span>
					<span id = "comments-likebtn_<?php echo $reply_comments_id."_".$comments_member_id."_".$loggedin_check; ?>" class="comments-likebtn comlike_gray "><a  href="javascript:void(0)" >拉馬</a> · </span>
					<span id = "reply_<?php echo $divreply_reply_com_id."_".$comments_member_id."_".$username."_".$nft_reply_rcid; ?>" class="replybtn" ><a href="javascript:void(0)">回覆</a></span>	
				</div >
				
			</div>
<?php		}
		}
	}
	else{
		echo false;
	}mysqli_close($con);
			
?>