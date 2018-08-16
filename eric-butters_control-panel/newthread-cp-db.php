<?php

	$newthread_success = false;
	if(isset($_POST["thread_subject"]) && !empty($_POST["thread_subject"]) && 
		isset($_POST["thread_uploadtype"]) && !empty($_POST["thread_uploadtype"]) &&
		isset($_POST["img_type"]) && !empty($_POST["img_type"])){
		
		header("Content-Type:text/html; charset=utf-8");
		include_once dirname(dirname(__FILE__)).'/dbconnect.php'; //兩個dirname可以取得上層目錄
		include_once dirname(dirname(__FILE__)).'/phpfunction.php';

		mysqli_set_charset($con, "utf8");

		$subject = mysqli_real_escape_string($con, $_POST['thread_subject']);
		$img_type = $_POST["img_type"];
		$upload_imgtype = "";
		$thread_uploadtype = $_POST["thread_uploadtype"];
		$date = get_nowdatetime();
		$ip_addr = get_client_ip();
		$image_path = "";
		$thumbnail = "";
		$image_savepath = dirname(dirname(__FILE__))."/thread-photo/";
		$image_success = false;

		if($thread_uploadtype==='file'){
			if(isset($_FILES['upload_img']['tmp_name']) && !empty($_FILES['upload_img']['tmp_name'])){
				if($_FILES['upload_img']['error']==0){
					$upload_img = $_FILES['upload_img']['tmp_name'];
					if($img_type==="image/gif"){
						if($image_path = gif_to_gfycat($upload_img , $thread_uploadtype)){
							$image_success = true;
							$upload_imgtype = "GIF";
							$thumbnail = thread_resize_crop_image($upload_img ,$image_savepath ,300 );
							$thumbnail = substr($thumbnail,strpos($thumbnail,"/thread") +1); //取得thread-photo/
						}
					}
					else if($image_path = thread_resize_crop_image($upload_img ,$image_savepath ,540 )){
						$image_path = substr($image_path,strpos($image_path,"/thread") +1); //取得thread-photo/
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
					$thumbnail = thread_resize_crop_image($upload_img ,$image_savepath ,300 );
					$thumbnail = substr($thumbnail,strpos($thumbnail,"/thread") +1); //取得thread-photo/
				}
			}
			else if($image_path = thread_resize_crop_image($upload_img ,$image_savepath ,540 )){
				$image_path = substr($image_path,strpos($image_path,"/thread") +1); //取得thread-photo/
				$image_success = true;
				$upload_imgtype = "JPG";
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
				$image_path = substr($image_path,strpos($image_path,"/thread") +1); //取得thread-photo/
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
		
			$sql_post_members = mysqli_query($con, "SELECT member_id FROM members WHERE member_type='post' ");
			$arr_num = 0;
			while($rows_post_members = mysqli_fetch_assoc($sql_post_members)) {
				$memberid_arr[$arr_num] = $rows_post_members['member_id']; //將hot留言的ID存入陣列，載入更多留言的時候使用
				$arr_num++;
			}
			$member_id = $memberid_arr[mt_rand(0 ,count($memberid_arr)-1)];

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