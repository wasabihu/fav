<?php
	require('../includes/application_top.php');
	
	
	$today = date("Y-m-d H:i:s");            



	$data =array('name'=>'test_cron','temp'=>'Test!','datetime'=>$today,'other'=>'');
	
	
	$wpdb->insert('test', $data);
	
	
	echo "$today";
	
	$data = file_get_contents ('http://121.9.213.44/api.myzaker.com/zakeruser/user_tag_rank/summary_rank.weekend_job2.php');
	
	echo $data;
	
	

?>