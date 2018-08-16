<?php
	include_once (dirname(__FILE__).'/set_session.php');
	unset($_SESSION['member_id']);
	header("location: https://llamafun.com/");
?>