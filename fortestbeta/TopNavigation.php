
<div class="top-navmenu">
	
	<ul id="nav" class="nav" >
	
			<?php
			if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){
			//檢查帳號是否有被BAN
			$sql_check_ban = mysqli_query($con, "SELECT ban FROM members WHERE member_id='$_SESSION[member_id]' ");
			$row_check_ban = mysqli_fetch_array($sql_check_ban);
			$ban = $row_check_ban['ban'];
			if($ban=='1'){
				unset($_SESSION['member_id']);
				header("location: /index.php");
			}
				
			$nav_member_id = $_SESSION['member_id'];
			$sql_get_username = mysqli_query($con, "SELECT * FROM member_profile WHERE member_id='$nav_member_id' ");
			$row = mysqli_fetch_assoc($sql_get_username);
			$username = $row['username'];
			
			$sql_notification_count = mysqli_query($con, "SELECT count_nft FROM notification WHERE rcv_member_id='$nav_member_id' AND count_nft='0' ");
			$all_notification_countRows = mysqli_num_rows($sql_notification_count);
			
			$sql_notification = mysqli_query($con, "SELECT * FROM notification WHERE rcv_member_id='$nav_member_id' LIMIT 8"); //判斷有超過7個訊息才讓卷軸載入更多通知有作用，設定最多搜尋到8個結果節省搜尋資源增加速度
			$all_notificationRows = mysqli_num_rows($sql_notification);
		?>
			<li><a href="/logout.php" class="logout_btn" >登出</a></li>
			
			<li><a href="/profile.php" >個人資料</a></li>
			<li id="notify_li">
			<?php if($all_notification_countRows>0){?>
				<span id="msg_count"><?php echo $all_notification_countRows;?></span>
			<?php }else{?>
				<span style="display: none;" id="msg_count"></span>
			<?php }?>
			<a href="javascript: void(0)" id="notifylink">通知</a>
			<div id="notificationContainer">
				<div id="notificationTitle">訊息通知
					<a href="javascript: void(0)" class="nft-readall" >全部已讀</a>
				</div>
				<div id="notificationsBody" class="notifications">
				
					<!--<div class="content"><img src="https://pbs.twimg.com/profile_images/624483066795290624/U_Qn1xLH_bigger.jpg" width="50px" height="50px" alt="profpic"> Developerdesks shared about something</div>
					<div class="content"><img src="https://pbs.twimg.com/profile_images/624483066795290624/U_Qn1xLH_bigger.jpg" width="50px" height="50px" alt="profpic"> Developerdesks liked page developerdesks</div>
					<div class="content"><img src="https://pbs.twimg.com/profile_images/624483066795290624/U_Qn1xLH_bigger.jpg" width="50px" height="50px" alt="profpic"> Developerdesks comment something</div>!-->

			<?php if($all_notificationRows>7){?>
					<div class="nft_loding" >載入中...</div>
			<?php }?>
				</div>
				<div id="notificationFooter"><a href="javascript: void(0)">顯示全部</a></div>
			</div>

			</li>
			<li><a href="/member-post.php?post_member_id=<?php echo $nav_member_id; ?>" class="loggedin_username" ><?php echo $username; ?></a></li>
		<?php } 
			else{?>
				<li><a href="javascript: void(0)" class="loginbtn" >登入</a></li>
				<li><a href="javascript: void(0)" class="registerbtn" >註冊</a></li>
		<?php	}?>
			
			<li class="llama_logo" ><a href="https://llamafun.com/"><img src="/image/llama_logo.png" border="0" ></a></li>
			<li class="index-hot" ><a href="https://llamafun.com/" >熱門</a></li>
			<li class="index-fresh"><a href="/index.php?post=fresh" >最新</a></li>
			<li class='nav-thread-post'><a href="javascript: void(0)" id="<?php echo $loggedin_check; ?>" class="newthreadbtn"  >發文</a></li>
	</ul>

	<div  id="side_nav_popup" class="side_nav_popup">
		<ul id="side-nav" class="side-nav" >
			<a href="javascript:void(0)" class="nav-close" >&times;</a>
			<?php if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){	?>
				<li><a href="javascript: void(0)" class="leaderboard_btn" >排行榜</a></li>
				<li><a href="/member-post.php?post_member_id=<?php echo $nav_member_id; ?>" class="loggedin_username" ><?php echo $username; ?></a></li>
				<li><a href="/member_notifications.php" class='side-nft-msg' >通知 <?php echo "(".$all_notification_countRows.")";?></a></li>
				<li><a href="/profile.php" >個人資料</a></li>
				<li><a href="/logout.php" class="logout_btn" >登出</a></li>
			<?php }
			else{?>
				<li><a href="javascript: void(0)" class="leaderboard_btn" >排行榜</a></li>
				<li><a href="javascript: void(0)" class="loginbtn" >登入</a></li>
				<li><a href="javascript: void(0)" class="registerbtn" >註冊</a></li>
			<?php	}?>
				
			<div class="site-rules">
				<div class='rules-title'>發文規則：</div>
				<div class='rule'>1.禁止張貼廣告</div>
				<div class='rule'>2.禁止張貼血腥照片</div>
				<div class='rule'>3.禁止張貼色情露點照片</div>
			</div>
			
			<div class="connect" >
				<div>想追蹤最新的趣圖可以給我們一個讚！</div>
				<div class="fb-like" data-href="https://www.facebook.com/LlamaFun-199457307259038/" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
				<div class="ig-follow">
					<a href="https://www.instagram.com/llamafun.ig/" target="_blank">在Instagram追蹤我們！</a>
					<a href="https://www.instagram.com/llamafun.ig/" target="_blank"><img src="image/instagram_icon.png" /></a>
				</div>
				<div class="connect-a">
					<div class="company">LlamaFUN © 2017</div>
					<a href="javascript:void(0)" class="contact-us" >聯絡我們</a>
				</div>
			</div>
		</ul>
	</div>
	
	<a class="toggle-nav" href="javascript: void(0)">&#9776;</a>
	<a class="rwd-llama_logo" href="https://llamafun.com/"><img src="/image/llama_logo.png" border="0" ></a>
	<a class="rwd-index-hot" href="https://llamafun.com/" >熱門</a>
	<a class="rwd-index-fresh" href="/index.php?post=fresh" >最新</a>
	<a  href="javascript: void(0)" id="<?php echo $loggedin_check; ?>" class="rwd-thread-post newthreadbtn"  >發文</a></li>
</div>
<?php 
include_once (dirname(__FILE__).'/newthread-popup.php'); 
include_once (dirname(__FILE__).'/leaderboard-popup.php');
include_once (dirname(__FILE__).'/contact-us-popup.php');
?>
