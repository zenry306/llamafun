 <!DOCTYPE html>
<?php
	header("Content-Type:text/html; charset=utf-8");
	include_once dirname(dirname(__FILE__)).'/dbconnect.php'; //兩個dirname可以取得上層目錄
?>

<html>
<head>
<title>後台管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include_once (dirname(__FILE__).'/css-and-js.php'); //include css和JS ?>
</head>
<body>
<?php
	include_once (dirname(__FILE__).'/panel-TopNavigation.php');
	if(isset($_GET["member_id"]) && !empty($_GET["member_id"])){ //顯示member_id所有的惡意檢舉
		$member_id = $_GET["member_id"];
		$sql_report = mysqli_query($con, "SELECT * FROM report WHERE report_judge='notguilty' AND member_id='$member_id' ORDER BY date DESC");
		echo "<div class='page_title'>".$member_id."</div>";
	}
	else{
		$sql_report = mysqli_query($con, "SELECT * FROM report WHERE report_judge='notguilty' ORDER BY date DESC");
	}
?>
<div class="page_title">惡意檢舉</div>
<div class="report_table">
      <div class="report_tr report_title">
          <div class="report_td">report_id</div>
		  <div class="report_td">member_id</div>
		  <div class="report_td">date</div>
		  <div class="report_td">report_reason</div>
		  <div class="report_td">report_member_id[guilty]</div>
		  <div class="report_td">type</div>
		  <div class="report_td">thread_id</div>
		  <div class="report_td">reply_comments_id</div>
		  <div class="report_td">report_content</div>
		  <div class="report_td">report_image</div>
      </div>

<?php
	while($row = mysqli_fetch_assoc($sql_report)) {
		$report_id = $row['report_id'];
		$member_id = $row['member_id'];
		$date = $row['date'];
		$report_reason = $row['report_reason'];
		$report_member_id = $row['report_member_id'];
		$type = $row['type'];
		$thread_id = $row['thread_id'];
		$reply_comments_id = $row['reply_comments_id'];
		$report_content = $row['report_content'];
		$image_type = $row['image_type'];
		$report_image = $row['report_image'];
		
		//$report_content = preg_replace('/\@([\w]+) /',  '', $report_content);
		//$regex = "/\@([\w]+)/";
		
		$sql_send_report = mysqli_query($con, "SELECT * FROM report WHERE report_judge='notguilty' AND member_id='$member_id' ");
		$send_report = mysqli_num_rows($sql_send_report);

		$sql_rev_report = mysqli_query($con, "SELECT * FROM report WHERE report_judge='guilty' AND report_member_id='$report_member_id' ");
		$rev_report = mysqli_num_rows($sql_rev_report);
		
		$notguilty_url = "https://".$_SERVER['SERVER_NAME']."/eric-butters_control-panel/report_notguilty.php?member_id=".$member_id;
		$guilty_url = "https://".$_SERVER['SERVER_NAME']."/eric-butters_control-panel/report_guilty.php?report_member_id=".$report_member_id;
			
			
?>	
	<div class="report_tr">
	  <div class="report_td"><?php echo $report_id; ?></div>
	  <div class="report_td"><?php echo "<a href=\"$notguilty_url\" target=_blank>".$member_id." — [".$send_report."次]</a>"; ?></div>
	  <div class="report_td"><?php echo $date; ?></div>
	  <div class="report_td"><?php echo $report_reason; ?></div>
	  <div class="report_td"><?php echo "<a href=\"$guilty_url\" target=_blank>".$report_member_id." — [".$rev_report."次]</a>"; ?></div>
	  
	  <div class="report_td"><?php echo $type; ?></div>
	  <div class="report_td"><?php echo $thread_id; ?></div>
	  <div class="report_td"><?php echo $reply_comments_id; ?></div>
	  <div class="report_td"><?php echo $report_content; ?></div>
	  <?php	if($image_type === 'JPG'){?>
		 <div class="report_td"><img class='report_image' src="<?php echo "https://www.llamafun.com/".$report_image; ?>" width="200px" /></div>
	  <?php }else if ($image_type === 'GIF'){ ?>
		<div class="report_td">
			<video class="report_image-gif" width="200" controls loop>
				<source src="<?php echo $report_image; ?>" type="video/mp4">Your browser does not support HTML5 video.
			</video>
		</div>
	 <?php }else{ ?>
		  <div class="report_td"></div>
	<?php } ?>
	</div>
	
	
	
<?php 
	}
?>
</div>

</body>
</html>




