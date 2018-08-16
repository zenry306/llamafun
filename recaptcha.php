 <?php
	if(isset($_POST["secret"]) && !empty($_POST["secret"]) && 
		isset($_POST["response"]) && !empty($_POST["response"])){

		$secret = $_POST['secret'];
		$response = $_POST['response'];

		$verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");

		$captcha_success=json_decode($verify);

		if ($captcha_success->success==true) {
			echo "success";
		}
		else if ($captcha_success->success==false) {
			echo "error";
		}

	}
?>