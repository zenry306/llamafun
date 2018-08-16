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
<?php include_once (dirname(__FILE__).'/panel-TopNavigation.php');?>
<div class="page_title">審判中</div>
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
		  <div class="report_td">審核</div>
      </div>

<?php 
/*
	$reply_image_path = "comment-photo/15035401543797668457.jpg";
	$reply_image_path = dirname(dirname(__FILE__)).'/'.$reply_image_path;
	echo $reply_image_path;
	unlink($reply_image_path);*/

	/*
	$sql_post_members = mysqli_query($con, "SELECT member_id FROM members WHERE member_type='post' ");
	$arr_num = 0;
	while($rows_post_members = mysqli_fetch_assoc($sql_post_members)) {
		$memberid_arr[$arr_num] = $rows_post_members['member_id']; //將hot留言的ID存入陣列，載入更多留言的時候使用
		$arr_num++;
	}

	$memberid_str = $memberid_arr[mt_rand(0 ,count($memberid_arr)-1)];
	echo $memberid_str."~~~~~~~~";
	print_r($memberid_arr);
	$member_id = "";*/

	$sql_report = mysqli_query($con, "SELECT * FROM report WHERE report_judge is null ORDER BY date DESC");
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
		
		$notguilty_url = "/eric-butters_control-panel/report_notguilty.php?member_id=".$member_id;
		$guilty_url = "/eric-butters_control-panel/report_guilty.php?report_member_id=".$report_member_id;
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
	  <div class="report_td"> <input type="text" class="report-reason" id="report-reason-<?php echo $report_id ?>" value="<?php echo $report_reason; ?>" > </div>
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
	   <div class="report_td">
			<div><a id='guilty_<?php echo $report_id; ?>' class="report-judge" href="javascript: void(0)" >有罪</a></div>
			<div><a id='notguilty_<?php echo $report_id; ?>' class="report-judge report-notguilty" href="javascript: void(0)" >惡意檢舉</a></div>
	   </div>
	</div>
	
	
	
<?php 
	}
?>
</div>
<?php 
	//echo dirname(__FILE__)."/thread-photo/";
	/*
		$image_path = dirname(dirname(__FILE__))."/thread-photo/test.txt";
		if(file_put_contents($image_path, "TESTETSTST")){
			echo "成功";
		}
		else{
			echo "失敗";
		}*/
$string_example= dirname(dirname(__FILE__))."/thread-photo/test.txt";
echo $string_example."~~";
echo strrchr($string_example, "/llamafun/");
// 輸出結果welcome to PJCHENder
//echo strrchr($string_example, "o");
// 輸出結果o PJCHENder
$str="https://www.google.com.tw/favicon.ico?act=http://blog.webgolds.com";
$last = substr($string_example,strpos($string_example,"/thread") +1); //不包含問號本身 ，用substr得到從?問號後面長度到剩下的全部字串
echo "<br>".$last;
?>
</body>
</html>




