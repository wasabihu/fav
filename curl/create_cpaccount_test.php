<?php
include('curl_funs.php');


$url = "http://www.cp1897.com.hk/api/user/register/";

$data = getCr_account_parameters_test();

$data['email'] = 'wasabi.gz@gmail.com';

wa_curl($url,$data);

echo "<br><br>==========================================data==<br>";
print_r($data);

die;


function getCr_account_parameters_test(){
	$data = array();
	
	$salt=mktime();
	
	$data['fav_bookType_cn'] = 'A01,A11';
	$data['specify_cn'] = 'YY@$@&&^%!';
	
	$data['fav_bookType_en'] = 'B25,B28';
	$data['specify'] = 'Odfdf';
		
	
	$data['email']	 = "wasatest_{$salt}@gmail.com";
	$data['nickname'] = "wasabiTest{$salt}";
	$data['password'] = '12345678';
	
	
	$data['lastname'] = 'hu';
	$data['firstname'] = 'Wasabi';
	
//	$data['phone_type'] = tep_db_input(reget('phone_type'));
//	$data['phone_area'] = tep_db_input(reget('phone_area'));
	
	$data['telephone'] = '123685525878';
	
	$data['country'] ='42';
	
	$data['inside_china'] = '185';
	$data['region'] = '廣州';
	
	$data['gender'] = 'M';
	
	$data['newsletter'] = '1';
	
	
	return $data;
}

?>