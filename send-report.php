
<?php
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	if(isset($_SESSION['member_id']) && !empty($_SESSION['member_id']) &&
	isset($_POST["report_type"]) && !empty($_POST["report_type"]) &&
	isset($_POST["report_com_thread_id"]) && !empty($_POST["report_com_thread_id"]) &&
	isset($_POST["report_reason"]) && !empty($_POST["report_reason"])){
			
		header("Content-Type:text/html; charset=utf-8");
		include_once (dirname(__FILE__).'/phpfunction.php');
		mysqli_set_charset($con, "utf8");

		$success = false;
		$member_id = $_SESSION['member_id'];
		$report_type = $_POST["report_type"];
		$report_reason = $_POST["report_reason"];
		$date = get_nowdatetime();
		$reply_comments_id = "";
		if($report_type==='檢舉-文章'){
			$thread_id = $_POST["report_com_thread_id"];
			$sql_thread_info = mysqli_query($con, "SELECT member_id, subject, image_type, image   FROM threads WHERE thread_id='$thread_id' ");
			$row = mysqli_fetch_array($sql_thread_info);
			$report_content = $row['subject'];
		}
		else if($report_type==='檢舉-留言'){
			$reply_comments_id = $_POST["report_com_thread_id"];
			$sql_comments_info = mysqli_query($con, "SELECT thread_id,member_id, comments, image_type, image   FROM reply_comments WHERE reply_comments_id='$reply_comments_id' ");
			$row = mysqli_fetch_array($sql_comments_info);
			$thread_id = $row['thread_id'];
			$report_content = $row['comments'];
		}

		$report_member_id = $row['member_id'];
		$image_type = $row['image_type'];
		$report_image = $row['image'];

		if($image_type==='JPG'){
			//備份原始圖片
			//$image = 'comment-photo/14958008348228454589.jpg';
			//echo strchr($string_example, "/");
			//echo $report_image;
			//$report_image;
			$image_backup  =  'report-image-backup/'.substr(strrchr($report_image, "/"), 1);
			if(copy($report_image, $image_backup)){
				$report_image = $image_backup;
			}
		}

		$report_id_check = true;
		while($report_id_check){
			$report_id = create_new_report_id();
			$sql_report_id_check = mysqli_query($con, "SELECT report_id FROM report WHERE BINARY report_id='$report_id' ");
			$rows_report_id = mysqli_num_rows($sql_report_id_check);
			if($rows_report_id<1){
				$report_id_check = false;
			}
		}
		$sql_send_report = "INSERT INTO report(report_id ,member_id , date , report_reason, report_member_id, type , thread_id , reply_comments_id , report_content , image_type , report_image) VALUES('$report_id' , '$member_id', '$date' ,'$report_reason', '$report_member_id' ,'$report_type' , '$thread_id' , '$reply_comments_id', '$report_content' ,'$image_type' ,'$report_image')";
		if($report_type==='檢舉-文章'){
			$sql_send_report = "INSERT INTO report(report_id ,member_id , date , report_reason, report_member_id, type , thread_id  , report_content , image_type , report_image) VALUES('$report_id' , '$member_id', '$date' ,'$report_reason', '$report_member_id' ,'$report_type' , '$thread_id' , '$report_content' ,'$image_type' ,'$report_image')";
		}
		
		if(mysqli_query($con, $sql_send_report)){
			//mysqli_query($con, "UPDATE member_profile SET send_report = send_report+1 WHERE member_id='$member_id' ");
			$success = true;
		}
		echo $success;
	}mysqli_close($con);
?>















