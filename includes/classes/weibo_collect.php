<?php
class Weibo_collect{

	const TABLE_NAME = 'user_account';
	 
	public $id;
	
	private $wpdb;
	private $sea_clienct;
	

	function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
		
		
// 		$_SESSION['token']['access_token'] = '2.00nqD2oBTRRd_Bf169b6b40d02Jd4b';
		if (empty($_SESSION['token']['access_token'])){
			die('empty access_token ,请重新授权一次!');
		}
		
		$this->sea_clienct = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	}
	
	
	function user_timeline_by_id($weibo_uid ,$max_id = 0){
		
		
		$ms = $this->sea_clienct->user_timeline_by_id($weibo_uid , $page = 1 , $count = 200 , $since_id = 0, $max_id = 0, $feature = 1, $trim_user = 1, $base_app = 0);
		
		return $ms;
	}
	
	function init_(){
		
	}
	
}	