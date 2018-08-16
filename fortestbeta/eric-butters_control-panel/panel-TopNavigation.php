<div class="top-navmenu">
	<ul id="nav" >
			<li><a href="/eric-butters_control-panel/report_judge.php">審判中</a></li>
			<li><a href="/eric-butters_control-panel/report_guilty.php" >有罪</a></li>
			<li ><a href="/eric-butters_control-panel/report_notguilty.php">惡意檢舉</a></li>
			<li><a href="/eric-butters_control-panel/ban-list.php">停權名單</a></li>
			<li><a href="javascript: void(0)" class="newthreadbtn-cp" >後台發文</a></li>
			<li>
				<span class="ban-account">
					<input type="text" id="ban-member-id" placeholder="輸入停權ID" />
					<a href="javascript: void(0)" class="ban-submit">送出</a>
					<span class="ban_msg" id="ban_msg" >BAN訊息</span>
				</span>
			</li>
			<li class="unban-account-li">
				<span class="unban-account">
					<input type="text" id="unban-member-id" placeholder="輸入復權ID" />
					<a href="javascript: void(0)" class="unban-submit">送出</a>
					<span class="unban_msg" id="unban_msg" >UNBAN訊息</span>
				</span>
			</li>
	</ul>
</div>

<?php 
include_once (dirname(__FILE__).'/newthread_popup_cp.php'); 
?>