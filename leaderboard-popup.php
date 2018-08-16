<div id="leaderboard_popup" class="leaderboard_popup">
	<div id="leaderboard-card" class="leaderboard-card">
		<span class="leaderboard_popup-close" title="關閉">&times;</span>
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
		<!--
		<div class="leaderboard-bottom-ads">
			<img src="\image\ads3.png" >
		</div>
		-->
	</div>
</div>