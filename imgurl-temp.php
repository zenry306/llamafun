<?php
	include_once (dirname(__FILE__).'/set_session.php');
	
	$newthread_success = false;
	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id']) && 
		isset($_POST["image_url"]) && !empty($_POST["image_url"])){

		include_once (dirname(__FILE__).'/phpfunction.php');
		
		$image_url = $_POST["image_url"];
	
		$image_path = "";
		$image_savepath = "temp-pic/";
		//$image_savepath = "thread-photo/";
		//echo $image_url;
		if($image_path = thread_resize_crop_image($image_url ,$image_savepath ,540 )){
			echo $image_path;
		}

	}
?>