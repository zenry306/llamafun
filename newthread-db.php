
<?php
	include_once (dirname(__FILE__).'/set_session.php');
	
	$newthread_success = false;
	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id']) && 
		isset($_POST["thread_subject"]) && !empty($_POST["thread_subject"]) && 
		isset($_POST["thread_uploadtype"]) && !empty($_POST["thread_uploadtype"]) &&
		isset($_POST["img_type"]) && !empty($_POST["img_type"])){
		
		header("Content-Type:text/html; charset=utf-8");
		include_once (dirname(__FILE__).'/dbconnect.php');
		include_once (dirname(__FILE__).'/phpfunction.php');

		mysqli_set_charset($con, "utf8");
		
		$member_id = $_SESSION['member_id'];
		$subject = mysqli_real_escape_string($con, $_POST['thread_subject']);
		$img_type = $_POST["img_type"];
		$upload_imgtype = "";
		$thread_uploadtype = $_POST["thread_uploadtype"];
		$date = get_nowdatetime();
		$ip_addr = get_client_ip();
		$image_path = "";
		$thumbnail = "";
		$image_savepath = "thread-photo/";
		$image_success = false;

		if($thread_uploadtype==='file'){
			if(isset($_FILES['upload_img']['tmp_name']) && !empty($_FILES['upload_img']['tmp_name'])){
				if($_FILES['upload_img']['error']==0){
					$upload_img = $_FILES['upload_img']['tmp_name'];
					if($img_type==="image/gif"){
						if($image_path = gif_to_gfycat($upload_img , $thread_uploadtype)){
							$image_success = true;
							$upload_imgtype = "GIF";
							$thumbnail = thread_resize_crop_image($upload_img ,$image_savepath ,540 );
						}
					}
					else if($image_path = thread_resize_crop_image($upload_img ,$image_savepath ,540 )){
						$image_success = true;
						$upload_imgtype = "JPG";
					}
				}
			}
		}
		else if($thread_uploadtype==='url'){
			$upload_img = $_POST["upload_img"];
			if($img_type==="image/gif"){
				if($image_path = gif_to_gfycat($upload_img , $thread_uploadtype)){
					$image_success = true;
					$upload_imgtype = "GIF";
					$thumbnail = thread_resize_crop_image($upload_img ,$image_savepath ,540 );
				}
			}
			else if($image_path = thread_resize_crop_image($upload_img ,$image_savepath ,540 )){
				$image_success = true;
				$upload_imgtype = "JPG";
				$thread_imgurl_temp = $_POST['thread_imgurl_temp'];
				unlink($thread_imgurl_temp); //刪除暫存圖片
			}
		}
		else if($thread_uploadtype==='canvas'){
			$upload_img = $_POST['upload_img'];
			$upload_img = str_replace('data:image/jpeg;base64,', '', $upload_img);
			$upload_img = str_replace(' ', '+', $upload_img);
			$canvas_img = base64_decode($upload_img);

			$image_path = $image_savepath.time().rand(1000000000, 9999999999).".jpg";
			if(file_put_contents($image_path, $canvas_img)){
				$image_success = true;
				$upload_imgtype = "JPG";
				$thread_imgurl_temp = $_POST['thread_imgurl_temp'];
				unlink($thread_imgurl_temp); //刪除暫存圖片
			}
		}
		if($image_success === true){
			$threadid_check = true;
			while($threadid_check){
				$thread_id = create_new_threadid();
				$sql_threadid_check = mysqli_query($con, "SELECT thread_id FROM threads WHERE BINARY thread_id='$thread_id' ");
				$rows_threadid = mysqli_num_rows($sql_threadid_check);
				if($rows_threadid<1){
					$threadid_check = false;
				}
			}
			$image_path = addslashes($image_path);
			if(mysqli_query($con, "INSERT INTO threads(thread_id , member_id, subject, date, image_type , image , thumbnail , who_like ,ip_address) VALUES('$thread_id' ,'$member_id', '$subject' , '$date', '$upload_imgtype' ,'$image_path' , '$thumbnail' ,' ' , '$ip_addr')")){
				$newthread_success = true;
			}
		}
	}
	if($newthread_success===true){
		echo $thread_id;
	}
	else{
		echo false;
	}
?>
