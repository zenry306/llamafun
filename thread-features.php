<div class = "div-row threadlike" ID = "threadlike_<?php echo $thread_id; ?>">
	<span class="likecount" id="likecount_<?php echo $thread_id; ?>" ><?php echo $likecount."隻" ?></span>
<?php if($loggedin_check === true && preg_match("/&$login_member_id/i", $who_like)){ ?>
	<span ><a id = "unlikebtn_<?php echo $thread_id."_".$member_id."_".$loggedin_check; ?>" class="likebtn shadow animate unlike-blue" href="javascript: void(0)" >拉馬</a></span>
<?php }
	 else {?>
	 <span><a id = "likebtn_<?php echo $thread_id."_".$member_id."_".$loggedin_check; ?>" class="likebtn shadow animate like-gray" href="javascript: void(0)" >拉馬</a></span>
<?php }?>
		<!--<span class="twitter">
			<a rel="nofollow" rel="noreferrer" href="javascript: void(0)" target="_blank">Twitter</a>
		</span>!-->
		<span class="fb">
			<a rel="nofollow" rel="noreferrer" href="javascript:void(0)" alt="<?php echo $share_threadurl ?>" target="_blank" class="facebook_share">Facebook</a>
		</span>
		<span class="line">
			<a  href="javascript: void(0)" alt="<?php echo $share_threadurl ?>" target="_blank" class="linebtn"> LINE傳送</a>
		</span>
		<span class="fb rwd-fb">
			<a rel="nofollow" rel="noreferrer" href="javascript:void(0)" alt="<?php echo $share_threadurl ?>" target="_blank" class="facebook_share"></a>
		</span>
		<span class="line rwd-line">
			<a  href="javascript: void(0)" alt="<?php echo $share_threadurl ?>" target="_blank" class="linebtn"></a>
		</span>
</div>
<div class="div-sendcom-a">
		<?php if($loggedin_check == true && $login_member_id === $member_id){
				if($thread_nft_onoff==='1'){
		?>
					<a id="thread-nft-off_<?php echo $thread_id; ?>" class="thread-nft-onoff" href="javascript: void(0)" >通知</a>
		<?php	 }
				else if($thread_nft_onoff==='0'){
		?>
					<a id="thread-nft-on_<?php echo $thread_id; ?>" class="thread-nft-onoff nft-off" href="javascript: void(0)" >通知</a>
		<?php 	} ?>
				<span ><a id = "thread-del_<?php echo $thread_id; ?>" class="thread-delete" href="javascript: void(0)" >刪除</a></span>
				<span ><a id = "thread-edit_<?php echo $thread_id; ?>" class="thread-edit" href="javascript: void(0)" >編輯</a></span>
		<?php }else{?>
				<a id = "thread-report_<?php echo $thread_id."_".$loggedin_check."_".$member_id; ?>" class="thread-com-report" href="javascript: void(0)" >檢舉</a>
		<?php }?>
		<a class="commentcount" id="commentcount_<?php echo $thread_id; ?>" href="<?php echo $threadurl.'#comments'; ?>" ><?php echo $row_comments_count." 則留言" ?></a>
</div>

<div class="div-sendcom-b">
	<textarea rows="1" maxlength="250" placeholder="留言..." class="textarea-comment" id="textarea-comment_<?php echo $thread_id; ?>" ></textarea>
	<!--<span class='com_limit'>250</span>-->
	<div class="div-sendcom-c" >
		<input placeholder="圖片URL" type="text" class="comment-image-url " id="comment-image-url_<?php echo $thread_id."_".$loggedin_check;?>" />
		<span class="comment-upload-submit">
			<a href="javascript: void(0)" class="comment-upload-file" id="comment-upload-file_<?php echo $thread_id;?>">上傳
				<input accept="image/gif, image/jpeg , image/png" type="file" class="comment-image-file" id="comment-image-file_<?php echo $thread_id."_".$loggedin_check;?>"  autocomplete="off">
			</a>
			<a href="javascript: void(0)" class="comment-upload-cancel" id="comment-upload-cancel_<?php echo $thread_id."_".$loggedin_check;?>" >取消</a>
			<a id = "submit-comment_<?php echo $thread_id."_".$loggedin_check; ?>" class="submit-comment" href="javascript: void(0)" >送出</a>
		</span>
	</div>
	<div class="comment-msg" id="comment-msg_<?php echo $thread_id; ?>"></div>
	<div class="send-comment-msg" id="send-comment-msg_<?php echo $thread_id; ?>">傳送中...</div>
</div>
