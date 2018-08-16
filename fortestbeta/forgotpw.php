 <?php
	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');
	mysqli_set_charset($con, "utf8");

	if(isset($_POST["email"]) && !empty($_POST["email"])){
		$email = mysqli_real_escape_string($con, $_POST['email']);

		$sql_email_check = mysqli_query($con, "SELECT password , member_type FROM members WHERE email='$email'");
		$rows_email_check = mysqli_num_rows($sql_email_check);
		
		if($rows_email_check<1 ){
			echo "notfound";
		}
		else{
			$rows = mysqli_fetch_assoc($sql_email_check);
			$member_type = $rows['member_type'];
			if($member_type=="FB"){
				echo "facebook";
			}
			else{
				require_once('./phpmailer/PHPMailerAutoload.php');
				
				$password = $rows['password'];
				$rows = mysqli_fetch_assoc($sql_email_check);
				$password = $rows['password'];
				$subject = "LlamaFUN - 密碼取回信";
				$msg = "您好：<br><br>您的帳號：".$email."<br>您的密碼：".$password."<br><br>如有其他問題可傳送EAMIL至support@llamafun.com或是到網站點選「聯絡我們」。<br><br>LlamaFUN 祝您有個 FUN day！"; 
				
				$mail= new PHPMailer();                          //建立新物件
				$mail->IsSMTP();                                 //設定使用SMTP方式寄信
				$mail->SMTPAuth = true;                          //設定SMTP需要驗證
				$mail->SMTPSecure = "ssl";                       //SMTP主機需要使用SSL連線
				$mail->Host = "mail.gandi.net";            		 //SMTP主機
				$mail->Port = 465;                               //的SMTP主機的埠號(SSL為465)。
				$mail->CharSet = "utf-8";                        //郵件編碼
				$mail->Username = "support@llamafun.com";        //帳號
				$mail->Password = "Rm6cj8j9ru%%%";               //密碼
				$mail->From = "support@llamafun.com";            //寄件者信箱
				$mail->FromName = "LlamaFUN";                    //寄件者姓名
				$mail->Subject ="$subject"; 	//郵件標題
				$mail->Body = "$msg"; 	 //郵件內容
				$mail->IsHTML(true);    //郵件內容為html
				$mail->AddAddress("$email");   //收件者郵件及名稱
				if(!$mail->Send()){
					echo "error";
				}
				else{
					echo "success";
				}
			}
		}
	}mysqli_close($con);
?>