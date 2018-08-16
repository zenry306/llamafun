<!DOCTYPE html>
<?php
	include_once (dirname(__FILE__).'/set_session.php');
	header("Content-Type:text/html; charset=utf-8"); 
	include_once (dirname(__FILE__).'/dbconnect.php');
	mysqli_set_charset($con, "utf8");
	
	if(!isset($_SESSION['member_id']) && empty($_SESSION['member_id'])){
		header("location: /index.php");
	}
	$loggedin_check = true;
	$login_member_id = $_SESSION['member_id'];
?>

<html>
<head>
<?php include_once (dirname(__FILE__).'/css-and-js.php'); //include css和JS ?>
<title>LlamaFUN - 個人資料</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
<meta name="description" content="LlamaFun是個充滿搞笑、趣圖、梗圖的好所在!趕快來發揮創意發表屬於自己的作品吧!" />
<meta name="googlebot" content="noarchive" />
<meta name="google" content="nositelinkssearchbox" />
<meta property="og:title" content="LlamaFUN - 搞笑、趣圖、梗圖的好所在" />
<meta property="og:site_name" content="LlamaFUN" />
<meta property="og:url" content="https://llamafun.com/" />
<meta property="og:description" content="LlamaFun是個充滿搞笑、趣圖、梗圖的好所在!趕快來發揮創意發表屬於自己的作品吧!" />
<meta property="og:type" content="website" />
<meta property="og:image" content="https://llamafun.com/image/llama_logo_og.png" />
<meta property="fb:app_id" content="1895947000621168" />

</head>
<body>
<?php
	include_once (dirname(__FILE__).'/TopNavigation.php');

	if($sql_member_id_date = mysqli_query($con, "SELECT email,member_type FROM members WHERE member_id='$login_member_id'")){
		$row_member_id_date = mysqli_fetch_assoc($sql_member_id_date);
		$member_id_email = $row_member_id_date['email'];
		$member_type = $row_member_id_date['member_type'];
	
		if($sql_member_profile = mysqli_query($con, "SELECT pic,username FROM member_profile WHERE member_id='$login_member_id'")){
		$row_member_profile = mysqli_fetch_assoc($sql_member_profile);
		$userpic = $row_member_profile['pic'];
		//$userpic_path = "http://".$_SERVER['SERVER_NAME']."/llamafun/".$userpic;
		$userpic_path = $userpic;
		$username = $row_member_profile['username'];
	
	
		
?>
<div class="profile-container">
		<div class="profile-title" >個人資料</div>
		<div class="edit-profile-div">
			<div class="profile-pic">
				<img src="<?php echo $userpic_path; ?>"width="120px" height="120px"/>
				<div class="profile-pic-right">
					<a href="javascript: void(0)" class="profile-upload-file" >瀏覽
						<input accept="image/gif, image/jpeg , image/png" type="file" class="profile-uploadpic" autocomplete="off" />
					</a>
					<a href="javascript: void(0)" class="profile-upload-cancel" >取消</a>
					<span><input type="text" class="profile-uploadpic-url" placeholder="圖片網址"  autocomplete="off"></span>
					<div class="profile-pic-tip">JPG、PNG、GIF、檔案最大：10MB</div>
					<div class="profile-pic-msg" id="">URL</div>
				</div>
			</div>
			<div class="profile-username">
				<div>暱稱</div>
				<input type="text" class="profile-edit-username" id="profile-edit-username" placeholder="暱稱" maxlength="12" value="<?php echo $username; ?>" >
				<div class="profile_errormsg" id="profile_error_username" >MSG</div>
			</div>
			<div class="profile-email">
				<div>信箱</div>
				<input type="email" class="profile-edit-email" id="profile-edit-email" placeholder="信箱" value="<?php echo $member_id_email; ?>" >
				<div class="profile_errormsg" id="profile_error_email" >MSG</div>
			</div>
	<?php	
			if($member_type!="FB"){
	?>		
			<div class="profile-password">
				<div class="profile-pw-title">密碼</div>
				<input type="password" id="profile-edit-pw-original" class="profile-edit-pw-original" maxlength="20" placeholder="舊密碼">
				<div class="profile_errormsg" id="profile_error_pw_original">MSG</div>
				
				<input type="password" id="profile-edit-pw-new" class="profile-edit-pw-new" maxlength="20" placeholder="新密碼" >
				<div class="profile_errormsg" id="profile_error_pw_new">MSG</div>
				
				<input type="password" id="profile-edit-pw-newcheck" class="profile-edit-pw-newcheck" maxlength="20" placeholder="新密碼確認" >
				<div class="profile_errormsg" id="profile_error_pw_newcheck">MSG</div>
			</div>
	<?php			
			}
			else{
	?>			
			<div class="fb_hide_pw">
			  <div class="profile-password">
				<div>修改密碼</div>
				<input type="password" id="profile-edit-pw-original" class="profile-edit-pw-original" maxlength="20" placeholder="舊密碼">
				<div class="profile_errormsg" id="profile_error_pw_original" >　</div>
				
				<input type="password" id="profile-edit-pw-new" class="profile-edit-pw-new" maxlength="20" placeholder="新密碼" >
				<div class="profile_errormsg" id="profile_error_pw_new" >　</div>
				
				<input type="password" id="profile-edit-pw-newcheck" class="profile-edit-pw-newcheck" maxlength="20" placeholder="新密碼確認" >
				<div class="profile_errormsg" id="profile_error_pw_newcheck" >　</div>
			  </div>
			</div>
	<?php			
			}
	?>		
			<a id = "submit-edit-profile" class="submit-edit-profile" href="javascript: void(0)">送出</a>
			<span class="profile_errormsg submit-profile-msg">MSG</span>
		</div>
		<!--
		<div class="profile-ads">
			<div>
				<img src="\image\ads2.png" >
			</div>
			<div>
				<img src="\image\ads.png" >
			</div>
		</div>
		<div class="profile-bottom-ads">
			<img src="\image\ads3.png" >
		</div>-->
</div>
<?php
		}
	}
		
?>
</body>
</html>