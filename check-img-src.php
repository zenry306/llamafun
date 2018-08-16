 <?php
	include_once (dirname(__FILE__).'/set_session.php');
	/*
	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');*/
	//mysqli_set_charset($con, "utf8");

	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id']) && 
		isset($_POST["comment_image"]) && !empty($_POST["comment_image"])){
		
		$comment_image_src = $_POST["comment_image"];
		$imgsrc_exist = false;
		
		$ch = curl_init($comment_image_src);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_NOBODY, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT,60);	//CURLOPT_TIMEOUT 可以設置為數秒，如果文件設定秒內沒有下載完成，腳本將會斷開連接
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,20); //可以設置為數秒，標識如果服務器設定的秒內沒有響應，腳本就會斷開連接
		//$data = curl_exec($ch);
		//$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		//$type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		//$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		

		if ($data = curl_exec($ch)) {
			$imgsrc_exist = true;
			//$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
			//$type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
			//$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			//echo $code."<br>";
			//echo $size/1024;
			//echo $type;
		}
		else{
			$imgsrc_exist = false;
		}
		curl_close($ch);
		echo $imgsrc_exist;
		
	}//mysqli_close($con);
?>