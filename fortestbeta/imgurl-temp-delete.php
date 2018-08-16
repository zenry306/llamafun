<?php
	if(isset($_POST["thread_imgurl_temp"]) && !empty($_POST["thread_imgurl_temp"])){

		$thread_imgurl_temp = $_POST["thread_imgurl_temp"];
		unlink($thread_imgurl_temp);

	}
?>