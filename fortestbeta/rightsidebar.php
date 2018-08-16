
<div class='rightsidebar'>
	<div class="leaderboard">
		<div class="leaderboard-title">排行榜</div>
		<div class="leaderboard-body">
			<?php	
				$sql_member_id_like = mysqli_query($con, "SELECT * FROM member_profile ORDER BY board_like DESC LIMIT 10");
				//$member_id_like_Rows = mysqli_num_rows($sql_member_id_like);
				while($row = mysqli_fetch_assoc($sql_member_id_like)){
					$board_member_id = $row['member_id'];
					$username = $row['username'];
					$board_like = $row['board_like'];
					$member_post_url = "/member-post.php?post_member_id=".$board_member_id;
			?>
				<div class="leaderboard-list">
					<span class="board-member-id"><?php echo "<a href=\"$member_post_url\" target=_blank>$username</a>"; ?></span>
					<span class="board-like"><?php echo $board_like."隻 拉馬"; ?></span>
				</div>
			<?php 		
				}?>
		</div>
	</div>
	
	<!--
	<div class='ads'>
		<img src="\image\ads2.png" >
	</div>
	
	<div class='ads'>
		<img src="\image\ads.png" >
	</div>-->
	
	<div class='site-rules'>
		<div class='rules-title'>發文規則：</div>
		<div class='rule'>1.禁止張貼廣告</div>
		<div class='rule'>2.禁止張貼血腥照片</div>
		<div class='rule'>3.禁止張貼色情露點照片</div>
	</div>
	<div class="connect" >
		想追蹤最新的趣圖可以給我們一個讚！
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
</div>