 <!DOCTYPE html>
<?php

	header("Content-Type:text/html; charset=utf-8");
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');

	
	mysqli_set_charset($con, "utf8");

	$loggedin_check = false;
	$login_member_id = " ";
	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){
		$login_member_id = $_SESSION['member_id'];
		$loggedin_check = true;
		//echo $login_member_id;
	}
	
	if(isset($_GET["thread_id"]) && !empty($_GET["thread_id"])){
	
		$thread_id = $_GET["thread_id"];
		
		$sql_thread = "SELECT  *  FROM threads WHERE thread_id='$thread_id' ";
		$result_thread = mysqli_query($con, $sql_thread);
		$row_thread_exist = mysqli_num_rows($result_thread);
		if($row_thread_exist<1){
			header("location: /index.php");
		}
		
		$row = mysqli_fetch_array($result_thread);
		$thread_id = $row['thread_id'];
		$member_id = $row['member_id'];
		//$thread_member_id = $row['member_id']; //因為底下載入TopNavigation.php時重複變數名稱$member_id
		$date = $row['date'];
		$subject = $row['subject'];
		$image_type = $row['image_type'];
		$image = $row['image'];  //GIF直接用這個
		//$image_path = "http://".$_SERVER['SERVER_NAME']."/llamafun/".$image; //不是GIF的話
		$og_image = "https://".$_SERVER['SERVER_NAME']."/".$image; //不是GIF的話
		$image_path = $image;
		$thumbnail = "https://".$_SERVER['SERVER_NAME']."/".$row['thumbnail'];//GIF用的縮圖
		$likecount = $row['thread_like'];
		$who_like = $row['who_like'];
		$thread_nft_onoff = $row['nft_onoff'];
		$threadurl = "https://".$_SERVER['SERVER_NAME']."/thread-show.php?thread_id=".$thread_id;
		$share_threadurl = "https://".$_SERVER['SERVER_NAME']."/thread-show.php?thread_id=".$thread_id;
		
	}
	else{
		header("location: /index.php");
	}
	
?>

<html>
<head>
<?php include_once (dirname(__FILE__).'/css-and-js.php'); //include css和JS ?>
<title>LlamaFUN - <?php echo $subject;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
<meta name="description" content="LlamaFun是個充滿搞笑、趣圖、梗圖的好所在!趕快來發揮創意發表屬於自己的作品吧!" />
<meta name="googlebot" content="noarchive" />
<meta name="google" content="nositelinkssearchbox" />
<meta property="og:title" content="<?php echo $subject; ?>"/>
<meta property="og:url" content="<?php echo $threadurl; ?>"/>
<?php if($image_type==="GIF"){?>
	<meta property="og:image" content='<?php echo $thumbnail; ?>'>
<?php 	
	}else{?>
	<meta property="og:image" content='<?php echo $og_image; ?>'>
<?php }?>

<meta property="og:site_name" content="LlamaFUN"/> 
<meta property="og:description" content="LlamaFun是個充滿搞笑、趣圖、梗圖的好所在!趕快來發揮創意發表屬於自己的作品吧!" />
<meta property="og:type" content="website" />
<meta property="fb:app_id" content="1895947000621168" />


</head>
<body>

<?php
	include_once (dirname(__FILE__).'/TopNavigation.php');
	include_once (dirname(__FILE__).'/login-register-popup.php');
	include_once (dirname(__FILE__).'/comment-img-popup.php');
	include_once (dirname(__FILE__).'/report-popup.php');
?>	
<div class="container">
<?php 
	include_once (dirname(__FILE__).'/rightsidebar.php');
?>

  <div class="move-div-table">
	<div class="div-table">
<?php
		

		$member_post_url = "/member-post.php?post_member_id=".$member_id;
		
		$sql_comments_count = mysqli_query($con, "SELECT thread_id FROM reply_comments WHERE thread_id='$thread_id'");
		$row_comments_count = mysqli_num_rows($sql_comments_count);
		
		$sql_member_id_info = mysqli_query($con, "SELECT * FROM member_profile WHERE member_id='$member_id'");
		$row_member_id_info = mysqli_fetch_assoc($sql_member_id_info);
		$total_like = $row_member_id_info['total_like'];
		$userpic = $row_member_id_info['pic'];
		//$userpic_path = "http://".$_SERVER['SERVER_NAME']."/llamafun/".$userpic;
		$userpic_path = $userpic;
		$username = $row_member_id_info['username'];
		//$threadurl = "/llamafun/fun/".$thread_id;
		//<div class="thread_show"></div> 用於首頁的編輯文章的功能使用
		?>
		
		  <div class="div-thread">
			<div class="thread_show"></div>
			<div class="thread-member-id-info rwd-thread-member-id-info"><?php echo '<a class="thread-member-id-info-img" href='.$member_post_url.' target=_blank><img src="'.$userpic_path.'"/></a>'; ?>
				<span class="info_username_llama">
					<a href="<?php echo $member_post_url;?>" target="_blank" ><?php echo $username; ?></a>
					<?php echo "拉馬 - ".$total_like."隻" ?>
				</span>
			</div>
			<div>
				<div id="thread-title_<?php echo $thread_id; ?>" class="div-row thread_title"><?php echo $subject; ?></div>
			</div>
			 <div class="thread-info-image">
				<div class="thread-member-id-info"><?php echo '<a class="thread-member-id-info-img" href='.$member_post_url.' target=_blank><img src="'.$userpic_path.'"/></a>'; ?>
					<span class="info_username_llama">
						<a href="<?php echo $member_post_url;?>" target="_blank" ><?php echo $username; ?></a>
						<?php echo "拉馬 - ".$total_like."隻" ?>
					</span>
				</div>
				<div>
				<?php if($image_type==="GIF"){?>
						<span class="div-row thread_image">
							
							<video class="gif-video" loop muted playsinline>
								<source src="<?php echo $image; ?>" type="video/mp4">Your browser does not support HTML5 video.
							</video>
							<div class="playpause"></div>
							<!-- <div class="rwd-playpause"></div> <!-- RWD用-->
						</span>
				<?php 	
					}else{?>
						<span class="div-row thread_image"><?php echo '<a href='.$threadurl.' target=_blank><img src="'.$image_path.'" width="600px"/></a>'; ?></span>
				<?php }?>
				</div>
			 </div>

			 <?php include (dirname(__FILE__).'/thread-features.php'); //載入按讚、分享、留言等功能?>
			 
			<div id="comments"></div>
			<div id="div-comments_<?php echo $thread_id; ?>" class="div-row div-comments">
				<?php
				

				
				//get rows query

				$sql_all_comments = mysqli_query($con, "SELECT * FROM reply_comments WHERE thread_id='$thread_id' and reply_to is null ORDER BY date DESC");
				//number of rows
				$all_commentsRows = mysqli_num_rows($sql_all_comments);

				//$row = mysqli_fetch_assoc($sql_comments);
				if($all_commentsRows > 0){
					$comments_showLimit = 5;
					$comments_showLimit_reply = 2;
					//$sql_comments_query = "SELECT * FROM reply_comments WHERE thread_id='$thread_id' and reply_to is null ORDER BY date DESC LIMIT ".$comments_showLimit;
					//$sql_comments_query = "SELECT * FROM reply_comments WHERE thread_id='$thread_id' and reply_to is null ORDER BY date DESC LIMIT ".$comments_showLimit;
					//$sql_comments_query = "SELECT  * , LOG10(comments_like + 1) * 287015 + UNIX_TIMESTAMP(date) AS Hotness  FROM reply_comments WHERE thread_id='$thread_id' and reply_to is null ORDER BY Hotness  DESC LIMIT ".$comments_showLimit;
					//$sql_comments_query = "SELECT  * , LOG10(comments_like + 1) * 287015 + UNIX_TIMESTAMP(date) AS Hotness  FROM reply_comments WHERE thread_id='$thread_id' and reply_to is null ORDER BY Hotness DESC , date DESC LIMIT 0 , 3";
					$sql_comments_query = "(SELECT  * , LOG10(comments_like + 1) * 287015 + UNIX_TIMESTAMP(date) AS Hotness  FROM reply_comments WHERE thread_id='$thread_id' and reply_to is null ORDER BY Hotness DESC LIMIT 2) 
											UNION
										(SELECT *, LOG10(comments_like + 1) * 287015 + UNIX_TIMESTAMP(date) AS Hotness FROM reply_comments WHERE thread_id='$thread_id' and reply_to is null ORDER BY date DESC LIMIT ".$comments_showLimit.")";
					$nft_rcid = "";
					if(isset($_GET["nft_rcid"]) && !empty($_GET["nft_rcid"]) ){
						$nft_rcid = $_GET["nft_rcid"];
						//echo "~~~~~~~~".$nft_rcid;
						$sql_comments_query = "SELECT * FROM reply_comments WHERE thread_id='$thread_id' and reply_to is null ORDER BY reply_comments_id = '$nft_rcid' DESC , date DESC LIMIT ".$comments_showLimit_reply;
						//$sql_comments_query = "SELECT * FROM reply_comments WHERE NOT reply_comments_id = '$nft_rcid' and thread_id='$thread_id' and reply_to is null ORDER BY date DESC LIMIT ".$comments_showLimit;
					}
					
					$sql_comments = mysqli_query($con, $sql_comments_query);
					$arr_num = 0;
					while($row = mysqli_fetch_assoc($sql_comments)){
						if($arr_num<2){ //因為只需存入前兩個HOT留言的ID
							$hot_comments_arr[$arr_num] = $row['reply_comments_id']; //將hot留言的ID存入陣列，載入更多留言的時候使用
							$arr_num++;
						}

						$reply_comments_id = $row['reply_comments_id'];
						$divreply_reply_com_id = $row['reply_comments_id'];
						$nft_reply_rcid = $row['reply_comments_id'];
						$showmore_date = $row['date'];
						$comments_member_id = $row['member_id'];
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
						
						<div class="comments-list" id="comments-list_<?php echo $reply_comments_id; ?>">
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
								<?php 	}
										$sql_check_replyto = mysqli_query($con, "SELECT reply_to FROM reply_comments WHERE reply_to = '$reply_comments_id' ");
										$row_check_replyto = mysqli_num_rows($sql_check_replyto);
										if($row_check_replyto<1){
								?>
											<a id = "comments-del_<?php echo $reply_comments_id; ?>" class="comments-delete" href="javascript: void(0)" >刪除</a>								
								<?php 	}
									  }?>
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
												<video class="comment-gif-video" width="170" loop autoplay muted playsinline>
													<source src="<?php echo $comments_image; ?>" type="video/mp4">Your browser does not support HTML5 video.
												</video>
								<?php		}
											else{?>
												<img class="comment-image"  src="<?php echo $comments_image_path; ?>" width="170px" />
								<?php		}
										}
								?>
							</div>
							
							<div class = "commentslike" ID = "commentslike_<?php echo $reply_comments_id; ?>">
								<span class="commentslikecount" id="commentslikecount_<?php echo $reply_comments_id; ?>" ><?php echo $comments_likecount."隻 · "?></span>
							<?php if($loggedin_check === true && preg_match("/&$login_member_id/i", $comments_wholike)){?>
								 <span id = "comments-unlikebtn_<?php echo $reply_comments_id."_".$comments_member_id."_".$loggedin_check; ?>" class="comments-likebtn comunlike_blue"><a  href="javascript:void(0)">拉馬</a> · </span> 
							<?php }
								 else {?>
								 <span id = "comments-likebtn_<?php echo $reply_comments_id."_".$comments_member_id."_".$loggedin_check; ?>" class="comments-likebtn comlike_gray "><a  href="javascript:void(0)" >拉馬</a> · </span> 
							<?php }?>
								 <span id = "reply_<?php echo $divreply_reply_com_id."_".$comments_member_id."_".$username."_".$nft_reply_rcid; ?>" class="replybtn" ><a href="javascript:void(0)">回覆</a></span>	
							</div>
							<div class="div-reply" id="div-reply_<?php echo $reply_comments_id; ?>">
						
				<?php 		
							$sql_allreply = mysqli_query($con, "SELECT * FROM reply_comments WHERE thread_id='$thread_id' AND reply_to ='$reply_comments_id' ORDER BY date ASC");
							$row_allreplyCount = mysqli_num_rows($sql_allreply);
							if($row_allreplyCount>0){
								$reply_rcid = "";
								$nft_target_rcid = "";
								
								$reply_showLimit = 1;
								$sql_reply_query = "SELECT * FROM reply_comments WHERE thread_id='$thread_id' AND reply_to ='$reply_comments_id' ORDER BY date ASC LIMIT ".$reply_showLimit;
								
								if(isset($_GET["reply_rcid"]) && !empty($_GET["reply_rcid"])){
									$reply_rcid = $_GET["reply_rcid"];
									$reply_showLimit = 2;
									$sql_reply_query = "SELECT * FROM reply_comments WHERE thread_id='$thread_id' AND reply_to ='$reply_comments_id' ORDER BY reply_comments_id = '$reply_rcid' DESC , date ASC LIMIT ".$reply_showLimit; 
									//ORDER BY reply_comments_id = '$reply_rcid' DESC 這邊要用DESC才會是排第一，不能用ASC
									if(isset($_GET["nft_target_rcid"]) && !empty($_GET["nft_target_rcid"])){
										$reply_showLimit = 3;
										$nft_target_rcid = $_GET["nft_target_rcid"];
										$sql_reply_query = "SELECT * FROM reply_comments WHERE thread_id='$thread_id' AND reply_to ='$reply_comments_id' ORDER BY reply_comments_id = '$nft_target_rcid' DESC , reply_comments_id = '$reply_rcid' DESC , date ASC LIMIT ".$reply_showLimit;
									}
								}
								
								
								$sql_reply = mysqli_query($con, $sql_reply_query);
								while($row_reply = mysqli_fetch_assoc($sql_reply)){
								$reply_comments_id = $row_reply['reply_comments_id'];
								$nft_reply_rcid = $row_reply['reply_comments_id'];
								$comments_member_id = $row_reply['member_id'];
								$reply_showmore_date = $row_reply['date'];
								$comments_wholike = $row_reply['who_like'];
								$comments_likecount = $row_reply['comments_like'];
								$comments_nft_onoff = $row_reply['nft_onoff'];
								$comments_image_type = $row_reply['image_type'];
								$comments_image = $row_reply['image']; //GIF直接用這個
								//$comments_image_path = "http://".$_SERVER['SERVER_NAME']."/llamafun/".$comments_image; //不是GIF的話
								$comments_image_path = $comments_image;
								$member_post_url = "/member-post.php?post_member_id=".$comments_member_id;
								
								$comments = $row_reply['comments'];
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
														<video class="comment-gif-video" width="170" loop autoplay muted playsinline>
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
								if($row_allreplyCount > $reply_showLimit){ 
			?>					
									<div class="reply_showmore_main" id="reply_showmore_main<?php echo $reply_comments_id; ?>"> 
										<span class="reply-showmore_<?php echo $reply_comments_id; ?>" ><a id="reply-showmore_<?php echo $thread_id."_".$reply_showmore_date."_".$divreply_reply_com_id."_".$reply_comments_id."_".$reply_rcid."_".$nft_target_rcid; ?>" class="reply_showmore" href="javascript: void(0)">顯示更多回覆</a></span>
										<span ><a style="display: none;" class="reply_loding" id="loding-<?php echo $reply_comments_id; ?>" href="javascript: void(0)" ><span class="loding_txt">載入中...</a></span></span>
									</div>
				<?php			}	?>
								
								
				<?php		} ?>
					
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
					
					if($all_commentsRows>$comments_showLimit){
						//獨立文章頁面不需要用 $showmore_date = "first-showmore"; //因為前兩個留言是HOT留言，所以初次按下顯示更多留言，以排除HOT留言然後用日期下去排序(新到舊)就會是最新的留言往下排序(以不更改JS架構所以這樣改)
						//$hot_comments_str = serialize($hot_comments_arr); //將hot留言ID的陣列轉為字串
						$hot_comments_str = implode("-",$hot_comments_arr); //將hot留言ID的陣列轉為字串用-分隔
				?>
						<div class="show_more_main" id="show_more_main<?php echo $thread_id."_".$divreply_reply_com_id; ?>">
							<a  id="show-more_<?php echo $thread_id."_".$showmore_date."_".$divreply_reply_com_id."_".$nft_rcid."_".$hot_comments_str; ?>" class="show_more show_more-white" href="javascript: void(0)">顯示更多留言</a>
							<span ><a style="display: none;" class="loding loding-white" id="loding-<?php echo $thread_id; ?>" href="javascript: void(0)"><span class="loding_txt">載入中...</a></span></span>
						</div>
			<?php 	}
				}		
			?>
			</div>
			<br><br>
		</div>
	</div>
</div>
</div>
	<div id="clear"></div>
	<?php 	

?>

</body>
</html>