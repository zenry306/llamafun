<?php
	
	function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	
	function get_nowdatetime(){
		$nowdatetime = "";
		date_default_timezone_set('Asia/Taipei');
		$nowdatetime = date("Y/m/d-H:i:s");
		
		return $nowdatetime;
	}
	
	function create_new_threadid(){
		$length = 8;
		$string = "";
		$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; // change to whatever characters you want
		while ($length > 0) {
			$string .= $characters[mt_rand(0,strlen($characters)-1)];
			$length -= 1;
		}
		return $string;
	}
	
	function create_fb_pw(){
		$length = 25;
		$string = "";
		$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; // change to whatever characters you want
		while ($length > 0) {
			$string .= $characters[mt_rand(0,strlen($characters)-1)];
			$length -= 1;
		}
		return $string;
	}
	
	function create_new_reply_comments_id(){
		$length = 8;
		$string = "";
		$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; // change to whatever characters you want
		while ($length > 0) {
			$string .= $characters[mt_rand(0,strlen($characters)-1)];
			$length -= 1;
		}
		return $string;
	}
	
	function create_new_memberid(){
		$length = 8;
		$string = "";
		$characters = "0123456789"; // change to whatever characters you want
		while ($length > 0) {
			$string .= $characters[mt_rand(0,strlen($characters)-1)];
			$length -= 1;
		}
		return $string;
	}
		
	function create_new_nftid(){
		$length = 8;
		$string = "";
		$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; // change to whatever characters you want
		while ($length > 0) {
			$string .= $characters[mt_rand(0,strlen($characters)-1)];
			$length -= 1;
		}
		return $string;
	}	
	
	function create_new_report_id(){
		$length = 8;
		$string = "";
		$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; // change to whatever characters you want
		while ($length > 0) {
			$string .= $characters[mt_rand(0,strlen($characters)-1)];
			$length -= 1;
		}
		return $string;
	}
	
	ini_set('memory_limit', '512M');
	//大頭貼function 舉例尺寸限定100*100，若大於這尺寸會擷取最中間並縮小至指定尺寸
	//若小於規定尺寸則判斷高寬是否相同，若相同則不處裡直接返回原圖，若不相同則以寬或高其中較小一邊，處理為方形
	function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
		$imgsize = getimagesize($source_file);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$mime = $imgsize['mime'];
		$dst_img = imagecreatetruecolor($max_width, $max_height);
		//所有格式都轉換回JPG 節省空間
		switch($mime){
			case 'image/gif':
			$image_create = "imagecreatefromgif";
			$image = "imagejpeg";
			$quality = 80;
			$type = ".jpg";
			//$image = "imagegif";
			//$type = ".gif";
			imagesavealpha($dst_img, true);
			$color = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
			imagefill($dst_img, 0, 0, $color);
			break;

			case 'image/png':
			$image_create = "imagecreatefrompng";
			//$image = "imagepng";
			$image = "imagejpeg";
			$quality = 80;
			$type = ".jpg";
			//$type = ".png";
			imagesavealpha($dst_img, true);
			$color = imagecolorallocatealpha($dst_img, 255, 255, 255, 0);
			imagefill($dst_img, 0, 0, $color);
			break;

			case 'image/jpeg':
			$image_create = "imagecreatefromjpeg";
			$image = "imagejpeg";
			$type = ".jpg";
			$quality = 80;
			break;

			default:
			return false;
			break;
		}

		
		$src_img = $image_create($source_file);
		if($width<=$max_width || $height <= $max_height){
			/*
			//小於規定尺寸例如100*100可是高寬相同直接傳回原圖並退出function
			if($width == $height){
				return $source_file; 
			}*/
			$sourceMin = min($width,$height);

			$dst_img = imagecreatetruecolor($sourceMin, $sourceMin);

			// 設定圖像的混色模式
			imagealphablending($dst_img, false);

			
			// 拷貝原圖到新圖
			$posX = $posY = 0;
			if($width > $height) {
				$posX = floor(($height-$width)/2);
			} else {
				$posY = floor(($width-$height)/2);
			}
				imagecopy($dst_img, $src_img, $posX, $posY, 0, 0, $width, $height);
		}
		else{
			$width_new = $height * $max_width / $max_height;
			$height_new = $width * $max_height / $max_width;
			//if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
			if($width_new > $width){
				//cut point by height
				$h_point = (($height - $height_new) / 2);
				//copy image
				imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
			}else{
				//cut point by width
				$w_point = (($width - $width_new) / 2);
				imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
			}
		}
		//$tmpname = addslashes(file_get_contents($dst_img));
		$dst_img_tempname = time().rand(1000000000, 9999999999);
		$dst_img_path = $dst_dir.$dst_img_tempname.$type;
		$image($dst_img, $dst_img_path , $quality);
		
		//return $dst_img;
		if($dst_img)imagedestroy($dst_img);
		if($src_img)imagedestroy($src_img);
		return $dst_img_path;
	}
	
	function thread_resize_crop_image($source_file, $dst_dir ,$max_width){
		$imgsize = getimagesize($source_file);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$mime = $imgsize['mime'];
		$new_width = $max_width;
		$new_height = $height*$new_width/$width;
		if($width<$new_width){ //如果圖片小於540或320(依照設定)就不做resize直接輸出原圖，若有PNG透明會透過case處理
			$new_width = $width;
			$new_height = $height;
			$dst_img = imagecreatetruecolor($new_width, $new_height);
		}
		else{
			$dst_img = imagecreatetruecolor($new_width, $new_height);
		}
		//所有格式都轉換回JPG 節省空間
		switch($mime){
			case 'image/gif':
			$image_create = "imagecreatefromgif";
			$image = "imagejpeg";
			//$quality = 80;
			$type = ".jpg";
			//$image = "imagegif";
			//$type = ".gif";
			imagesavealpha($dst_img, true);
			$color = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
			imagefill($dst_img, 0, 0, $color);
			break;

			case 'image/png':
			$image_create = "imagecreatefrompng";
			//$image = "imagepng";
			$image = "imagejpeg";
			$type = ".jpg";
			//$type = ".png";
			imagesavealpha($dst_img, true);
			$color = imagecolorallocatealpha($dst_img, 255, 255, 255, 0);
			imagefill($dst_img, 0, 0, $color);
			break;

			case 'image/jpeg':
			$image_create = "imagecreatefromjpeg";
			$image = "imagejpeg";
			$type = ".jpg";
			break;

			default:
			return false;
			break;
		}
		$src_img = $image_create($source_file);
		imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		$dst_img_tempname = time().rand(1000000000, 9999999999);
		$dst_img_path = $dst_dir.$dst_img_tempname.$type;
		$quality = 50;
		$image($dst_img, $dst_img_path , $quality);
		if($dst_img)imagedestroy($dst_img);
		if($src_img)imagedestroy($src_img);
		return $dst_img_path;
	}
	
	
	function gif_to_gfycat($gif_img , $upload_type){
		$upload_succese = false;
		$token_data = array(
			'client_id'=>'2_dX2rbX',
			'client_secret'=>'ud40ZTebGHhrupn0A4qDinwiSOl9xg-F5iVChg1sNB2YUsawp7rIDVCWdcLsEooq' ,
			'grant_type'=>'client_credentials',
		);
		$token_data_string = json_encode($token_data); 
		
		for ( $i=0; $i<5 ;$i++ ){
		
		$ch = curl_init('https://api.gfycat.com/v1/oauth/token');    
		//curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		//curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_VERBOSE, true);   
		//CURLOPT_POST => true
		//curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $token_data_string);                                                                
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT,80);	//CURLOPT_TIMEOUT 可以設置為數秒，如果文件設定秒內沒有下載完成，腳本將會斷開連接
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,50); //可以設置為數秒，標識如果服務器設定的秒內沒有響應，腳本就會斷開連接
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($token_data_string))                                                                       
		);                                                                                                             
		$token=curl_exec($ch);
		curl_close($ch);

		if($token!=false){
			$json_token = json_decode($token, true);
			if(isset($json_token['access_token']) && !empty($json_token['access_token'])){
				if($upload_type==="file"){
					$ch = curl_init('https://api.gfycat.com/v1/gfycats');
					// -v
					curl_setopt($ch, CURLOPT_VERBOSE, true);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                                                                                    
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_TIMEOUT,80);	//CURLOPT_TIMEOUT 可以設置為數秒，如果文件設定秒內沒有下載完成，腳本將會斷開連接
					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,50); //可以設置為數秒，標識如果服務器設定的秒內沒有響應，腳本就會斷開連接
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
						'Content-Type: application/json',                                                                                                                                                     
					));                                                                                                
					$gfyname = curl_exec($ch);
					curl_close($ch);
					if($gfyname!=false){
						$json_gfyname = json_decode($gfyname, true);
						if(isset($json_gfyname['gfyname']) && !empty($json_gfyname['gfyname'])){
							$upload_key_gfyname = $json_gfyname['gfyname'];
							$cfile = curl_file_create($gif_img);
							$data = array(
								'key'=>$upload_key_gfyname,
								"file"=>$cfile,
							);
							$ch = curl_init('https://filedrop.gfycat.com');
							curl_setopt($ch, CURLOPT_HEADER, true);
							curl_setopt($ch, CURLOPT_VERBOSE, true); 
							//curl_setopt($ch, CURLOPT_POST, true);
							curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
							curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_TIMEOUT,80);	//CURLOPT_TIMEOUT 可以設置為數秒，如果文件設定秒內沒有下載完成，腳本將會斷開連接
							curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,50); //可以設置為數秒，標識如果服務器設定的秒內沒有響應，腳本就會斷開連接
							curl_setopt($ch, CURLOPT_HTTPHEADER, array(
								//'Content-Type: application/json',
								"Content-Type:multipart/form-data",                                                                                                                                                     
							));
                                                                                                
							$upload_data = curl_exec($ch);
							curl_close($ch);
							if($upload_data!=false){
								$get_gfy_info = "https://api.gfycat.com/v1/gfycats/fetch/status/".$upload_key_gfyname;

								$check_task = true;
								while($check_task){
									sleep(5);//等待數秒，避免太快讀取gfycat那邊還沒處理好，拿不到資訊

									$ch = curl_init($get_gfy_info);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_TIMEOUT,80);	//CURLOPT_TIMEOUT 可以設置為數秒，如果文件設定秒內沒有下載完成，腳本將會斷開連接
									curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,50); //可以設置為數秒，標識如果服務器設定的秒內沒有響應，腳本就會斷開連接
									$gfy_info = curl_exec($ch);
									curl_close($ch);

									if($gfy_info!=false){
										$json_gfy_info = json_decode($gfy_info, true);
										if($json_gfy_info['task']!='encoding'){
											if(isset($json_gfy_info['mp4Url']) && !empty($json_gfy_info['mp4Url'])){
												$mp4_url = $json_gfy_info['mp4Url'];
												$upload_succese = true;
											}
											$check_task = false;
										}
									}
									else{ //如果false的話
										$check_task = false;
									}
								}
							  }
							}
						}
					}
					else if($upload_type==="url"){
						$data = array(
							'fetchUrl'=>$gif_img,
							'title'=>'This is a title',
						);
						$data_string = json_encode($data);
						
						$ch = curl_init('https://api.gfycat.com/v1/gfycats');

						//curl_setopt($ch, CURLOPT_HEADER, true);
						curl_setopt($ch, CURLOPT_VERBOSE, true); 
						//curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_TIMEOUT,80);	//CURLOPT_TIMEOUT 可以設置為數秒，如果文件設定秒內沒有下載完成，腳本將會斷開連接
						curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,50); //可以設置為數秒，標識如果服務器設定的秒內沒有響應，腳本就會斷開連接
						curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
							'Content-Type: application/json',
							//"Content-Type:multipart/form-data",                                                                                                                                                     
						));

						$get_gfyname=curl_exec($ch);
						curl_close($ch);
						if($get_gfyname!=false){
							$json_get_gfyname = json_decode($get_gfyname, true);
							if(isset($json_get_gfyname['gfyname']) && !empty($json_get_gfyname['gfyname'])){
								$gfyname = $json_get_gfyname['gfyname'];
								$get_gfy_info = "https://api.gfycat.com/v1/gfycats/fetch/status/".$gfyname;
								
								
								$check_task = true;
								while($check_task){
									sleep(5);//等待數秒，避免太快讀取gfycat那邊還沒處理好，拿不到資訊

									$ch = curl_init($get_gfy_info);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_TIMEOUT,80);	//CURLOPT_TIMEOUT 可以設置為數秒，如果文件設定秒內沒有下載完成，腳本將會斷開連接
									curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,50); //可以設置為數秒，標識如果服務器設定的秒內沒有響應，腳本就會斷開連接
									$gfy_info = curl_exec($ch);
									curl_close($ch);

									if($gfy_info!=false){
										$json_gfy_info = json_decode($gfy_info, true);
										if($json_gfy_info['task']!='encoding'){
											if(isset($json_gfy_info['mp4Url']) && !empty($json_gfy_info['mp4Url'])){
												$mp4_url = $json_gfy_info['mp4Url'];
												$upload_succese = true;
											}
											$check_task = false;
										}
									}
									else{ //如果false的話
										$check_task = false;
									}
								}
							}
						}
					}
				}
			}
			if($upload_succese === true){
				break;
			}
			
		}
		
		if($upload_succese === true){
			return $mp4_url;
		}
		else{
			return false;
		}
	}
	
	
?>