<?php
if(isset($_POST["thread_id"]) && !empty($_POST["thread_id"]) &&
	isset($_POST["comments_date"]) && !empty($_POST["comments_date"] &&
	isset($_POST["divreply_reply_com_id"]) && !empty($_POST["divreply_reply_com_id"]) &&
	isset($_POST["reply_com_id"]) && !empty($_POST["reply_com_id"]) )){

	//include database configuration file
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	mysqli_set_charset($con, "utf8");

	$loggedin_check = false;
	$login_member_id = " ";
	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){
		$login_member_id = $_SESSION['member_id'];
		$loggedin_check = true;
		//echo $login_member_id;
	}
	
	$comments_date = $_POST["comments_date"];
	$thread_id = $_POST["thread_id"];
	$divreply_reply_com_id = $_POST["divreply_reply_com_id"];
	$reply_comments_id = $_POST["reply_com_id"];
	
	$reply_rcid = "";
	$nft_target_rcid = "";
	
	$showLimit = 2;
	$sql_queryAll = "SELECT COUNT(*) as num_rows FROM reply_comments WHERE thread_id='$thread_id' and date > '$comments_date' and reply_to ='$divreply_reply_com_id' ORDER BY date ASC";
	$sql_query = "SELECT * FROM reply_comments WHERE thread_id='$thread_id' and date > '$comments_date' and reply_to ='$divreply_reply_com_id' ORDER BY date ASC LIMIT ".$showLimit;
	
	if(isset($_POST["reply_rcid"]) && !empty($_POST["reply_rcid"])){
		$reply_rcid = $_POST["reply_rcid"];
		$sql_queryAll = "SELECT COUNT(*) as num_rows FROM reply_comments WHERE NOT reply_comments_id = '$reply_rcid' and thread_id='$thread_id' and date > '$comments_date' and reply_to ='$divreply_reply_com_id' ORDER BY date ASC";
		$sql_query = "SELECT * FROM reply_comments WHERE NOT reply_comments_id = '$reply_rcid' and thread_id='$thread_id' and date > '$comments_date' and reply_to ='$divreply_reply_com_id' ORDER BY date ASC LIMIT ".$showLimit;
		if(isset($_POST["nft_target_rcid"]) && !empty($_POST["nft_target_rcid"])){
			$nft_target_rcid = $_POST["nft_target_rcid"];
			
			$sql_queryAll = "SELECT COUNT(*) as num_rows FROM reply_comments WHERE NOT reply_comments_id = '$reply_rcid' and NOT reply_comments_id = '$nft_target_rcid' and thread_id='$thread_id' and date > '$comments_date' and reply_to ='$divreply_reply_com_id' ORDER BY date ASC";
			$sql_query = "SELECT * FROM reply_comments WHERE NOT reply_comments_id = '$reply_rcid' and NOT reply_comments_id = '$nft_target_rcid' and thread_id='$thread_id' and date > '$comments_date' and reply_to ='$divreply_reply_com_id' ORDER BY date ASC LIMIT ".$showLimit;
			
		}
	}
	
	
	//count all rows except already displayed
	$queryAll = mysqli_query($con,$sql_queryAll);
	$row = mysqli_fetch_assoc($queryAll);
	$allRows = $row['num_rows'];

	//get rows query
	$query = mysqli_query($con, $sql_query);
	
	//number of rows
	$row_commentsCount = mysqli_num_rows($query);

	if($row_commentsCount > 0){ 
		while($row = mysqli_fetch_assoc($query)){ 
			
			$reply_comments_id = $row['reply_comments_id'];
			$nft_reply_rcid = $row['reply_comments_id'];
			$comments_member_id = $row['member_id'];
			$reply_showmore_date = $row['date'];
			$comments_wholike = $row['who_like'];
			$comments_likecount = $row['comments_like'];
			$comments_nft_onoff = $row['nft_onoff'];
			$comments_image_type = $row['image_type'];
			$comments_image = $row['image']; //GIF直接用這個
			//$comments_image_path = "http://".$_SERVER['SERVER_NAME']."/llamafun/".$comments_image; //不是GIF的話
			$comments_image_path = $comments_image; //不是GIF的話
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
					<?php if($login_member_id !== $comments_member_id){	?>
							<a id = "comments-report_<?php echo $thread_id."_".$loggedin_check."_".$comments_member_id."_".$reply_comments_id; ?>" class="thread-com-report" href="javascript: void(0)" >檢舉</a>
					<?php }
						  else{
							if($comments_nft_onoff=='1'){
					?>
								<a id="comment-nft-off_<?php echo $thread_id; ?>" class="comment-nft-onoff" href="javascript: void(0)" >關閉這個文章的通知</a>
					<?php	 }
							else if($comments_nft_onoff=='0'){
					?>
								<a id="comment-nft-on_<?php echo $thread_id; ?>" class="comment-nft-onoff" href="javascript: void(0)" >開啟通知</a>
					<?php 	} ?>
							<a  id = "reply-del_<?php echo $reply_comments_id;; ?>" class="reply-delete" href="javascript: void(0)" >刪除</a>
					<?php }?>
					  </div>
					</div>
				</div>
				<div class="comments-comments">
					<?php echo nl2br($comments);
							if($comments_image != null){
								if(isset($comments) && !empty($comments)){
									echo "<br>"; //如果$comments不是空的才空行
								}
								if($comments_image_type==="GIF"){?>
									<video class="comment-gif-video" width="170" loop autoplay muted playsinline >
										<source src="<?php echo $comments_image; ?>" type="video/mp4">Your browser does not support HTML5 video.
									</video>
					<?php		}
								else{?>
									<img class="comment-image"  src="<?php echo $comments_image_path; ?>" width="170px" />
					<?php		}
							}
					?>
				</div>
				<div class = "commentslike reply-commentslike" ID = "commentslike_<?php echo $reply_comments_id; ?>">
					<span class="commentslikecount" id="commentslikecount_<?php echo $reply_comments_id; ?>" ><?php echo $comments_likecount."隻 · "?></span>
				<?php if($loggedin_check === true && preg_match("/&$login_member_id/i", $comments_wholike)){?>
					 <span id = "comments-unlikebtn_<?php echo $reply_comments_id."_".$comments_member_id."_".$loggedin_check; ?>" class="comments-likebtn comunlike_blue"><a  href="javascript:void(0)">拉馬</a> · </span> 
				<?php }
					 else {?>
					 <span id = "comments-likebtn_<?php echo $reply_comments_id."_".$comments_member_id."_".$loggedin_check; ?>" class="comments-likebtn comlike_gray "><a  href="javascript:void(0)" >拉馬</a> · </span> 
				<?php }?>
					 <span id = "reply_<?php echo $divreply_reply_com_id."_".$comments_member_id."_".$username."_".$nft_reply_rcid; ?>" class="replybtn" ><a href="javascript:void(0)">回覆</a></span>	
				</div>
			</div>
	<?php 

			}
		}
	?>
	<?php if($allRows > $showLimit){ ?>
				<div class="reply_showmore_main" id="reply_showmore_main<?php echo $reply_comments_id; ?>"> 
					<span class="reply-showmore_<?php echo $reply_comments_id; ?>" ><a id="reply-showmore_<?php echo $thread_id."_".$reply_showmore_date."_".$divreply_reply_com_id."_".$reply_comments_id."_".$reply_rcid."_".$nft_target_rcid; ?>" class="reply_showmore" href="javascript: void(0)">顯示更多回覆</a></span>
					<span ><a style="display: none;" class="reply_loding" id="loding-<?php echo $reply_comments_id; ?>" href="javascript: void(0)" ><span class="loding_txt">載入中...</a></span></span>
				</div>
	<?php } ?>  
		
	<?php 
} 
?>
