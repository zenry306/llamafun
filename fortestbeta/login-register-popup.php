<div id="login_popup" class="login_popup">
	<div id="login-card" class="login-card">
	<div class="login_title">登入</div>
	<!--<span onclick="document.getElementById('login_popup').style.display='none'" class="login_popup-close" title="關閉">&times;</span>-->
	<a href="javascript: void(0)" class="login_popup-close" title="關閉" >&times;</a>
		<form id="login_form" action="login_submit.php">
			<input type="email" id="login_email" maxlength="200" placeholder="信箱" onkeydown="login_pressenter(event)">
			<input type="password" id="login_password" maxlength="20" placeholder="密碼" onkeydown="login_pressenter(event)">
		</form>
		<div  align="center" id="reCAPTCHA_LOGIN"></div>
		  <span><a href="javascript: void(0)" class="popup_loginbtn login-submit" >登入</a> </span>
		  <span> <a href="javascript: void(0)" class="facebook_login facebook_reg" >使用Facebook登入</a> </span>
	  <div align="center" class="login_message" id="login_message">登入訊息</div>
	  
	  <div class="login-help">
		<a href="javascript: void(0)" class="login-to-register">註冊</a> • <a href="javascript: void(0)" class="forgotpw">忘記密碼嗎？</a>
	  </div>
	</div>
</div>

<div id="forgotpw_popup" class="forgotpw_popup">
	<div id="forgotpw-card" class="forgotpw-card">
	<div class="forgotpw_title">輸入信箱以取回密碼</div>
	<a href="javascript: void(0)" class="forgotpw-card-close" title="關閉" >&times;</a>
		<input type="email" id="forgotpw_email" placeholder="信箱" >
		<span><a href="javascript: void(0)" class="forgotpw-submit" >送出</a> </span>
		<div align="center" class="forgotpw_message" id="forgotpw_message">MSG</div>
	</div>
	<div class="forgotpw-finished">
		<a href="javascript: void(0)" class="forgotpw-card-close" title="關閉" >&times;</a>
		<div class="forgotpw_title">已寄送您的密碼至信箱</div>
	</div>
</div>

<div id="register_popup" class="register_popup">
	<div id="register-card" class="register-card">
	<div class="register_title">註冊</div>
	<span class="register_popup-close" title="關閉">&times;</span>
		<div>
			<input type="text" id="register_username" maxlength="12" placeholder="暱稱" onkeydown="register_pressenter(event)">
			<div class="register_errormsg" id="error_username" >暱稱</div>
			<!--<div class="register_errormsg" id="error_account" >暱稱</div>
				<input type="text" id="register_account" placeholder="暱稱" onkeydown="register_pressenter(event)">!-->
			<input type="email" id="register_email"  maxlength="200" placeholder="信箱" onkeydown="register_pressenter(event)" />
			<div class="register_errormsg" id="error_email" >信箱</div>
			
			<input type="password" id="register_password" maxlength="20" placeholder="密碼" onkeydown="register_pressenter(event)">
			<div class="register_errormsg" id="error_password" >密碼</div>
			
			<input type="password" id="register_password_check" maxlength="20" placeholder="密碼確認" onkeydown="register_pressenter(event)">
			<div class="register_errormsg" id="error_password_check" >密碼確認</div>
		</div>
		<div  align="center" id="reCAPTCHA_REG"></div>
	  <span><a href="javascript: void(0)" class="popup_registerbtn register-submit" >註冊</a></span>
	  <span> <a href="javascript: void(0)" class="facebook_register facebook_reg" >使用Facebook註冊</a> </span>
	  <div align="center" class="register_message" id="register_message">註冊訊息</div>
	  <div class="register-help">
		<a href="javascript: void(0)" class="register-to-login" >已有帳號點此登入</a>
	  </div>
	</div>
</div>