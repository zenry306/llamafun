
<?php
	include_once (dirname(__FILE__).'/set_session.php');
	include_once (dirname(__FILE__).'/dbconnect.php');
	include_once (dirname(__FILE__).'/phpfunction.php');
	mysqli_set_charset($con, "utf8");
	
	if(isset($_POST["thread_id"]) && !empty($_POST["thread_id"]) &&
		isset($_POST["subject"]) && !empty($_POST["subject"]) &&
		isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])){

		$member_id = $_SESSION['member_id'];
		$thread_id = $_POST["thread_id"];
		$subject = mysqli_real_escape_string($con, $_POST['subject']);
		$date = get_nowdatetime();
		
		if(mysqli_query($con, "UPDATE threads SET subject = '$subject' , editdate = '$date'  WHERE thread_id='$thread_id' AND member_id='$member_id' ")){
			/*
			$sql_thread = mysqli_query($con, "SELECT  subject  FROM threads WHERE thread_id='$thread_id' ");
			$row = mysqli_fetch_array($sql_thread);
			$subject = $row['subject'];*/
			
			echo true;
?>		
			
			<!--<div class="div-row thread_title"><?php //echo $subject;?></div> -->

<?php			
		}
		else{
			echo false;
		}
	}mysqli_close($con);
?>			
