<?php
include('curl_funs.php');

$url = "http://www.cp1897.com.hk/api/info/getretails/";
$data = array('code'=>'RS32');

wa_curl($url,$data);

die;



	
	