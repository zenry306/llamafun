 <!DOCTYPE html>
 <?php
	header("Content-Type:text/html; charset=utf-8");
	include_once dirname(dirname(__FILE__)).'/dbconnect.php'; //兩個dirname可以取得上層目錄
?>

<html>
<head>
<title>後台管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="/llamafun/css/css-control-panel.css" media="screen" type="text/css" charset="utf-8" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script type="text/javascript" src="/llamafun/js/control-panel.js" charset="utf-8"></script>
</head>
<body>
<?php
	include_once (dirname(__FILE__).'/panel-TopNavigation.php');

	$sql_ban_list = mysqli_query($con, "SELECT * FROM members WHERE ban='1' ORDER BY date DESC");

?>
<div class="page_title">停權名單</div>
<div class="ban_table">
      <div class="ban_tr ban_title">
		  <div class="ban_td">member_id</div>
		  <div class="ban_td">member_type</div>
		  <div class="ban_td">email</div>
		  <div class="ban_td">password</div>
		  <div class="ban_td">date</div>
		  <div class="ban_td">editdate</div>
		  <div class="ban_td">ip_address</div>
		  <div class="ban_td">ban</div>
      </div>

<?php  
	
	while($row = mysqli_fetch_assoc($sql_ban_list)) {
		$member_id = $row['member_id'];
		$member_type = $row['member_type'];
		$email = $row['email'];
		$password = $row['password'];
		$date = $row['date'];
		$editdate = $row['editdate'];
		$ip_address = $row['ip_address'];
		$ban = $row['ban'];
		
		$sql_rev_report = mysqli_query($con, "SELECT * FROM report WHERE report_judge='guilty' AND report_member_id='$member_id' ");
		$rev_report = mysqli_num_rows($sql_rev_report);
		
		//$notguilty_url = "http://".$_SERVER['SERVER_NAME']."/llamafun/eric-butters_control-panel/report_notguilty.php?member_id=".$member_id;
		$guilty_url = "http://".$_SERVER['SERVER_NAME']."/llamafun/eric-butters_control-panel/report_guilty.php?report_member_id=".$member_id;
		
?>	
	<div class="ban_tr">
	  <div class="ban_td"><?php echo "<a href=\"$guilty_url\" target=_blank>".$member_id." — [".$rev_report."次]</a>"; ?></div>
	  <div class="ban_td"><?php echo $member_type; ?></div>
	  <div class="ban_td"><?php echo $email; ?></div>
	  <div class="ban_td"><?php echo $password; ?></div>
	  <div class="ban_td"><?php echo $date; ?></div>
	  <div class="ban_td"><?php echo $editdate; ?></div>
	  <div class="ban_td"><?php echo $ip_address; ?></div>
	  <div class="ban_td"><?php echo $ban; ?></div>
	</div>
	
	
	
<?php 
	}
?>
</div>

</body>
</html>




