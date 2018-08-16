 <?php
	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');
	mysqli_set_charset($con, "utf8");

	if(isset($_POST["username"]) && !empty($_POST["username"])&&
		isset($_POST["email"]) && !empty($_POST["email"]) &&
		isset($_POST["category"]) && !empty($_POST["category"]) &&
		isset($_POST["subject"]) && !empty($_POST["subject"]) &&
		isset($_POST["content"]) && !empty($_POST["content"])){
			
		$username = mysqli_real_escape_string($con, $_POST['username']);
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$category = $_POST['category'];
		$subject = mysqli_real_escape_string($con, $_POST['subject']);
		$content = mysqli_real_escape_string($con, $_POST['content']);
		$content = preg_replace('#(\\\r|\\\r\\\n|\\\n)#', '<br/>', $content);
		$subject = "[".$category."] ".$subject;
		require_once('./phpmailer/PHPMailerAutoload.php');
		
		$mail= new PHPMailer();                          //建立新物件
		$mail->IsSMTP();                                 //設定使用SMTP方式寄信
		$mail->SMTPAuth = true;                          //設定SMTP需要驗證
		$mail->SMTPSecure = "ssl";                       //SMTP主機需要使用SSL連線
		$mail->Host = "mail.gandi.net";            		 //SMTP主機
		$mail->Port = 465;                               //的SMTP主機的埠號(SSL為465)。
		$mail->CharSet = "utf-8";                        //郵件編碼
		$mail->Username = "support@llamafun.com";        //帳號
		$mail->Password = "Rm6cj8j9ru%%%";               //密碼
		$mail->From = "$email";            				 //寄件者信箱
		$mail->FromName = "$username";                    //寄件者姓名
		$mail->Subject ="$subject"; 	//郵件標題
		$mail->Body = "$content"; 	    //郵件內容
		$mail->IsHTML(true);            //郵件內容為html
		$mail->AddAddress("support@llamafun.com");    //收件者郵件及名稱
		if(!$mail->Send()){
			echo "error";
		}
		else{
			echo "success";
		}
	}mysqli_close($con);
?>