<?php
/**
 * 綁定門市會員
 */

include('curl_funs.php');


$url = "http://www.cp1897.com.hk/api/user/registercpstore/";

$data = get_cpstore_account_parameters_test();


$data['username'] = 'wasabi.gz@gmail.com';
$data['password'] = '12345678';


wa_curl($url,$data);

echo "<br><br>==========================================data==<br>";
print_r($data);

die;


function get_cpstore_account_parameters_test(){
	$data = array();
	
	$salt=mktime();
	
	//會員卡信息
	$data['member_type'] = 'GM';
	$data['card_number'] = '0100120179';
	$data['card_code'] = 'NXQRSY';
	
	$data['cardRenewalBranch'] = 'RS01';
	
	$data['add_flat']	 = "add_flat";
	$data['addr_floor'] = "addr_floor";
	$data['addr_block'] = 'add_block';
	$data['add_building'] = 'add_building';
	$data['add_street'] = 'add_street';
	$data['add_district'] = 'add_district';
	
	$data['education'] ='42';
	$data['occupation'] = '185';
	
	$data['income'] = '5000';
	
	$data['idCard_type'] = 'HKID';
	$data['idCard_no'] = '198652541';
	
	$data['dob'] = '1980-10-29';
	
	return $data;
}

?>