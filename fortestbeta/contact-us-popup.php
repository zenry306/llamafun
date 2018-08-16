<div id="contact_popup" class="contact_popup">
	<div id="contact-card" class="contact-card">
		<span class="contact_popup-close" title="關閉">&times;</span>
		<div class="contact_title">聯絡我們</div>
		<div>
			<input type="text" id="contact_username" maxlength="12" placeholder="暱稱" />
				<div class="contact_errormsg" id="con_error_username" >暱稱</div>
			
			<input type="email" id="contact_email" maxlength="200" placeholder="信箱" />
				<div class="contact_errormsg" id="con_error_email" >信箱</div>
			
			<div class="contact_category-div">
				<select class="contact_category">
					<option value="choose">請選擇類別...</option>
					<option value="bugreport">錯誤回報</option>
					<option value="suggestion">建議</option>
					<option value="question">問題</option>
					<option value="other">其他</option>
				</select>
			</div>
			<div class="contact_errormsg" id="con_error_category" >類別</div>
			
			<input type="text" id="contact_subject" maxlength="60" placeholder="標題" />
				<div class="contact_errormsg" id="con_error_subject" >標題</div>
			<div class="contact_content-div">
				<textarea maxlength='2000' class="contact_content" id="contact_content" placeholder="內容..." ></textarea>
					<div class="contact_errormsg" id="con_error_content" >內容</div>
			</div>
		</div>
		<div class="contact-submit-div"><a href="javascript: void(0)" class="contact-submit" >送出</a></div>
		<div class="contact_errormsg" id="con_error_submit" >MSG</div>
	</div>
	<div class='contact_finished'>
		<span class="contact_popup-close" title="關閉">&times;</span>
		<div class="contact_title">感謝您的來信~我們會盡快處理</div>
	</div>
</div>