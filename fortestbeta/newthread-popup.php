<div id="is-dragover" class="is-dragover">
	<div class="dragover-text">拖放上傳</div>
</div>
<div id="newthread_popup" class="newthread_popup">
	<div id="newthread-upload" class="newthread-upload">
	<span class="newthread_popup-close" title="關閉">&times;</span>
		<div class="drag-drop-tip">拖曳圖片</div>
		<div class="newthread-title">選擇圖片檔案或圖片網址</div>
		<div>
			<a href="javascript: void(0)" class="thread-upload-file" id="thread-upload-file">選擇檔案
				<input id="file" class="thread_uploadimg" type="file" accept="image/gif, image/jpeg , image/png" autocomplete="off">
			</a>
			
		</div>
		<div>
			<input type="text" id="url" class="thread_uploadimg_url" placeholder="圖片網址" />
			<div class="uploadimg_url-error">URL</div>
		</div>
	</div>
	<div class="newthread-post">
		<span class="newthread_post-close" title="關閉">&times;</span>
		<div class="div-thread_subject">
			<textarea maxlength='60' class="thread-subject" id="thread-subject" placeholder="輸入標題..." ></textarea>
			<div id="thread_subject_msg" class="thread_subject_msg">標題訊息</div>
		</div>
		
		<canvas id="meme-canvas" class="meme-canvas" width="0" height="0"></canvas>
		<img class="preview-gif" src="" >
		<div class="makememe-div">
			<div class="makememe-div-right">
				<div>
					<div class="topline-div">
						<textarea id="topline" class="meme-inputtext" placeholder="圖片上排文字" autocomplete="off"></textarea>
						<div class="fontsize-div">
							<a class="meme_fontsize"	id="topline_increase" rel="nofollow" rel="noreferrer" href="javascript:void(0)" >+</a>
							<a class="meme_fontsize"	id="topline_decrease" rel="nofollow" rel="noreferrer" href="javascript:void(0)" >&minus;</a>
						</div>
					</div>
					<div class="bottomline-div">
						<textarea id="bottomline" class="meme-inputtext" placeholder="圖片下排文字" autocomplete="off"></textarea>
						<div class="fontsize-div">
							<a class="meme_fontsize"	id="bottomline_increase" rel="nofollow" rel="noreferrer" href="javascript:void(0)" >+</a>
							<a class="meme_fontsize"	id="bottomline_decrease" rel="nofollow" rel="noreferrer" href="javascript:void(0)" >&minus;</a>
						</div>
					</div>
				</div>
				<div class="pre_postbtn">
					<a class="post-backtoup" href="javascript:void(0)" >上一步</a>
					<a class="newthread-send" href="javascript:void(0)" >發文</a>
				</div>
			</div>
		</div>
		
		

		
	</div>
</div>

