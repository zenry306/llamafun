<?php

	include_once (dirname(__FILE__).'/set_session.php');
	
	//include_once (dirname(__FILE__).'/dbconnect.php');
	//include_once (dirname(__FILE__).'/phpfunction.php');
	//mysqli_set_charset($con, "utf8");

	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id']) && 
		isset($_POST["image_url"]) && !empty($_POST["image_url"])){
		
		$image_url = $_POST["image_url"];
		//$imgsrc_exist = false;
		
		//$image_url = 'https://upload.wikimedia.org/wikipedia/commons/f/fc/Chartley_Castle-1.jpg';
		$ch = curl_init($image_url);
		//$fp = fopen('temp-pic/flower3.jpg', 'wb');
		//curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_NOBODY, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT,60);	//CURLOPT_TIMEOUT 可以設置為數秒，如果文件設定秒內沒有下載完成，腳本將會斷開連接
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,20); //可以設置為數秒，標識如果服務器設定的秒內沒有響應，腳本就會斷開連接
		
		if ($data = curl_exec($ch)) {
			$img_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
			//$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			//$img_type = $imgsize['mime']; //type
			if($img_type==="image/png" || $img_type==="image/jpeg" || $img_type==="image/jpg" || $img_type==="image/gif"){
				$img_size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
				if(($img_size/1024/1024)<=10){
					echo $img_type;
					//echo $img_size/1024/1024;
				}
				else{
					echo "圖片太大";
				}
			}
			else{
				echo "不支援網址";
			}
			//echo $img_type;
			//$imgsrc_exist = true;
		}
		else{
			echo "網址錯誤";
			//$imgsrc_exist = false;
		}
		curl_close($ch);

	//	echo $imgsrc_exist;
	

	}
	/*
	$ch = curl_init('http://www.raincoast.org/wp-content/uploads/S1I1596grizzlysittingattideline547-10MB.jpg');
	$fp = fopen('temp-pic/flower2.jpg', 'wb');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);*/
	
	
	/*
	$url = 'http://www.raincoast.org/wp-content/uploads/S1I1596grizzlysittingattideline547-10MB.jpg';
	$img = 'temp-pic/flower.jpg';
	file_put_contents($img, file_get_contents($url));*/
	
	/*
	$image_url = 'http://www.raincoast.org/wp-content/uploads/S1I1596grizzlysittingattideline547-10MB.jpg';
	if ($imgsize=getimagesize($image_url)) {
		echo "有圖片";
	}
	else{
		echo "沒圖片";
	}*/
	/*

	//}


// outputs e.g.  somefile.txt: 1024 bytes
/*
$filename = 'https://www.cleverfiles.com/howto/wp-content/uploads/2016/08/mini.jpg';
$filesize ="";
echo $filename . ': ' . filesize($filename)/1024/1024 . ' bytes';
*/
/*
 $ch = curl_init('https://www.cleverfiles.com/howto/wp-content/uploads/2016/08/mini.jpg');

 curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
 curl_setopt($ch, CURLOPT_HEADER, TRUE);
 curl_setopt($ch, CURLOPT_NOBODY, TRUE);

 $data = curl_exec($ch);
 $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

 curl_close($ch);
 echo $size/1024;
*/
/*
		$image_path = 'https://www.cleverfiles.com/howto/wp-content/uploads/2016/08/mini.jpg';
		$imgsize = getimagesize($image_path);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$mime = $imgsize['mime']; //type
		echo $mime;
  */
 //echo "type=".mime_content_type($image_path);

?>
		