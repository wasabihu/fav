<?php
	require('../includes/application_top.php');
	
	
	$today = date("Y-m-d H:i:s");            
	echo "$today <br>";
	
	$data = file_get_contents ('http://121.9.213.44/api.myzaker.com/zakeruser/user_tag_rank/recent_rank.weekend_job1.php');
	
	echo $data;
	
	

?>