 <?php
	if(isset($_POST["fb_access_token"]) && !empty($_POST["fb_access_token"])){
		$fb_access_token = $_POST["fb_access_token"];
		$fb_AppID = '1895947000621168';
		$fb_AppSecret = '6087a156cf27bc1116aaa13798aa5240';
		$debug_token = "https://graph.facebook.com/debug_token?input_token=".$fb_access_token."&access_token=".$fb_AppID."|".$fb_AppSecret;
		$ch = curl_init($debug_token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);	//CURLOPT_TIMEOUT 可以設置為數秒，如果文件設定秒內沒有下載完成，腳本將會斷開連接
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10); //可以設置為數秒，標識如果服務器設定的秒內沒有響應，腳本就會斷開連接
		$token_response = curl_exec($ch);
		curl_close($ch);
		$json_token_response = json_decode($token_response, true);
		//echo "<br><br><br><br><br><br><br>".$token_response;
		//echo "<br>".$json_token_response->data->app_id;
		//echo "<br>".$json_token_response['data']['app_id'];
		//echo $json_token_response['data']['app_id'];
		//echo $json_token_response['data']['application'];
		//echo $json_token_response['data']['is_valid'];
		$json_app_id = $json_token_response['data']['app_id'];
		$json_is_valid = $json_token_response['data']['is_valid'];
		if($json_app_id===$fb_AppID && $json_is_valid==true ){
			echo "success";
		}
		else{
			echo false;
		}
	}
?>