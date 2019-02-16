<?php


class User{

   const TABLE_NAME = 'user_account';
   
  public $id;

  public $user_info;
  
  private  $wpdb;

  function __construct($user_id='') {
  	global $wpdb;
  	  	
  	$this->wpdb = $wpdb;
  	
  	if(!empty($user_id) && is_numeric($user_id))
  		$this->getUserInfo($user_id);
  	
//   	return $this->user_info;

  }
  
  
  
  function getUserInfo($user_id){
  	global $wpdb;

  	$sql = $wpdb->prepare("select * FROM user_account where uid='%s'", $user_id);
  	
  	$uInfo = $wpdb->get_row($sql);
  	
//   	print_r($uInfo);
  	
  	$this->user_info = $uInfo;
 
  	$this->id = $this->user_info['uid']; 
  	
  	return $this->user_info;

  }
  
  
  
  
 public static function getUserInfo_array($user_id){
  	global $wpdb;

  	$sql = $wpdb->prepare("select * FROM user_account where WUID='%s'", $user_id);
  	return $wpdb->get_row($sql);
  }
  
  public static function logout(){
  	
  	
  	unset($_SESSION['user_id']);
  	unset($_SESSION['user_info']);
  	
  	unset($_SESSION['curr_user']);
  	
  	
     $time = time() - 3600;
     
     setcookie('WF[uid]',  '', $time);
     setcookie('WF[domain]', '', $time);
     setcookie('WF[nickname]',    '', $time); 
     setcookie('WF[user_email]',    '', $time); 
     
     setcookie('WF[avatars_path]',    '', $time);
     

  }
  
  public static function setUserSession($user_info){
  	
  	if(empty($user_info) && empty($user_info['uid']))	return false;
  	
  	
  	$_SESSION['user_id'] = $user_info['uid'];
  	$_SESSION['user_info'] = $user_info;
  	
  	User::setUserCookie($user_info['uid'],$user_info);
  }
  
public static function setUserCookie($user_id='', $user_info=array()){
	if (empty($user_id))	return false;

//	print_r($user_info); die;
        /* 设置cookie */
        $time = time() + 3600 * 24 * 30;
        
        setcookie("WF[uid]",  $user_id,   $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
        setcookie("WF[domain]", $user_info['domain'] , $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
        
        
        setcookie("WF[avatars_path]", $user_info['avatars_path'] , $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
        
        
        
        
        setcookie("WF[nickname]", $user_info['nickname'] , $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
        setcookie("WF[user_email]",    $user_info['user_email'],     $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
}

public static function getCookie_userInfo(){
	
	$data = array();
	
	
	$data['uid'] 		= $_COOKIE['WF']['uid'];
	$data['domain'] 	= $_COOKIE['WF']['domain'];
	$data['nickname'] 	= $_COOKIE['WF']['nickname'];
	$data['user_email'] = $_COOKIE['WF']['user_email'];
	
	$data['avatars_path'] = $_COOKIE['WF']['avatars_path'];
	
	return $data;
}

  /**
   * 得到當前user_id 通過Session和cookie來獲取
   * 
   */
  public static function getCurr_id(){
  	if(! empty($_SESSION['user_id']))	return $_SESSION['user_id'];
  	
  	if(! empty($_COOKIE['WF']['uid']))	return $_COOKIE['WF']['uid'];
  	
  	
  	return 0;
  	
//   	if(IS_LOCAL)	return DEF_UID;
 	
  	
  }
  
  public static function getUser_id($user_email){
  	global $wpdb;
  	
  	$sql = $wpdb->prepare("select uid FROM user_account where user_email='%s'", $user_email);
  	
  	return $wpdb->get_var($sql);
  }
  
  public static function getUserInfo_formData(){
  	global $wpdb;
  	$data =array();
  	$data['WUNAME'] =  $_POST['nickname'];
  	
  	$data['WUEMAIL'] =  strtolower($_POST['email']);

  	$data['PASSWORDAES'] =  aecEncrypt($_POST['password']) ;
  	
  	
  	$data['email'] =  $data['WUEMAIL'];
  	
  	$data['WUTYPE'] =  'juan';
  	$data['RECOMMENDER'] =  'null';
  	
  	
  	
  	return $wpdb->_escape($data);
  	
  }
  
  
  public static function insert($user_data){
  	global $wpdb;
  	
  //如果数据没有问题，就插入user
  
	if(self::check_userData($user_data)){
		
		//插入user_account 表
		$wpdb->insert(User::TABLE_NAME ,$user_data);
		
		
		return $user_data['uid'];
	}
	else return false;
  }
  
  public static function update($user_data,$user_id){
  	global $wpdb;
  	
  	if(empty($user_id))	return false;
  	
  	$where_arr = array('uid'=>$user_id);
  	
  	
  	 $bool = $wpdb->update(self::TABLE_NAME,$user_data,$where_arr);
  	 
  	 
  	 $user = new User($user_id);
//	 User::setUserSession($user->user_info);
  	 
  	 return $bool;
  }
  
  
  public static function check_userData($data){
  	
  	if(empty($data['user_email']))	return false;
  	
  	return filter_var($data['user_email'],FILTER_VALIDATE_EMAIL);
  	
  }
  
  public static function check_userEmail_exist($email){
  	global $wpdb;
  	
  	$sql = $wpdb->prepare("select user_email from ".User::TABLE_NAME." where user_email='%s'",$email);
  	
  	return $wpdb->get_var($sql);
  }
  
 public static function is_sina_connect($email){
  	global $wpdb;
  	
  	$sql = $wpdb->prepare("select WUEMAIL from ".User::TABLE_NAME." where WUTYPE='sina' AND WUEMAIL='%s'",$email);	
  	
//  	echo "$sql<br>";
  	
  	return $wpdb->get_var($sql);
  }
  
  
  
  //儲存usermeta數據
  public static function save_usermeta($user_id,$meta_key,$meta_value){
  	global $wpdb;
  	
  	$meta_key = preg_replace('|[^a-z0-9_]|i', '', $meta_key);
  	
  	if(empty($user_id) || empty($meta_key))	return false;
  	
  	
  	if ( is_string($meta_value) )
		$meta_value = stripslashes($meta_value);
  	
  	$meta_value = maybe_serialize($meta_value);
  		
  	$sql = "delete from usermeta where meta_key='$meta_key' AND user_id='$user_id'";
  	
  	$wpdb->query($sql);
  	
  	
  	$data=array('user_id'=>$user_id,'meta_key'=>$meta_key,'meta_value'=>$meta_value);
  	
  	$wpdb->insert('usermeta',$data);
  	
  }
  
  
  public static function update_usermeta($user_id,$meta_key,$meta_value){
  	
  	self::save_usermeta($user_id,$meta_key,$meta_value);
  	
//  	global $wpdb;
//  	if(empty($user_id) || empty($meta_key))	return false;
//  	
//  	$data=array('meta_value'=>$meta_value);
//  	
//  	
//  	$where_data=array('user_id'=>$user_id,'meta_key'=>$meta_key);
//  	
//  	$wpdb->update('usermeta',$data,array( 'user_id' => $user_id ), array( '%s', '%s' ));
  	
  	
  }
  
  
  
  public static function get_avatars_path($user_id){
  	
  	//是否远程图片
  	$is_remote = self::get_usermeta($user_id,'is_remote');
  	
  	$avatars_path = self::get_usermeta($user_id,'avatars_path');
  	
//  	echo "$user_id <br>";
  	if(empty($is_remote)){
  		if(empty($avatars_path)) $avatars_path =AVATARS_UPLOADFILE.'defaut_header.jpg';
  	
  		else $avatars_path = AVATARS_UPLOADFILE.$avatars_path;
  	}
  	

  	return $avatars_path;
  }
  
  
  
public static function get_usermeta($user_id,$meta_key){
  	global $wpdb;
  	
  	if(empty($user_id) || empty($meta_key))	return false;
  	
  	$meta_key = preg_replace('|[^a-z0-9_]|i', '', $meta_key);
  	
  	
  	$sql = "select meta_value from usermeta where user_id='$user_id' AND meta_key='$meta_key' limit 1";
  	

//  	echo "$sql <br>";
  	$metas = $wpdb->get_col($sql);
  	
  	
  	if(empty($metas))	return 0;
  	
  	$metas = array_map('maybe_unserialize', $metas);
  	
//  	if($meta_key=='points')	print_r($metas);
  	
  	if ( count($metas) == 1 )	return $metas[0];
	else						return $metas;
	
  }
  

  public static function get_usermeta_all($user_id){
  	global $wpdb,$smarty;
  	
  	$usermeta =array();
  	
  	//得到頭像路徑
	$usermeta['avatars_path'] = self::get_avatars_path($user_id);
	
//	$smarty->assign('avatars_path',$avatars_path);
	
	
	//得到積分
	$usermeta['points']  = self::get_usermeta($user_id,'points');
	
	
	return $usermeta;
	
//	$smarty->assign('usermeta',$usermeta);
  	
  }
  
  
  public static function login($data){
  	global $wpdb;
  	if(empty($data['email']) || empty($data['password']))	return false;
  	
  	//如果不是E-mail 格式，直接返回。
  	if(! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) return false;
  	
  	$sql = "select * from ".self::TABLE_NAME." where WUEMAIL='$data[email]' AND PASSWORDAES='$data[password]'";

  	return $wpdb->get_row($sql);
  	
  }
  
  
public static function share_login($email){
  	global $wpdb;
  	
  	if(empty($email))	return false;
  	
  	$sql = "select * from ".self::TABLE_NAME." where user_email='$email'";

  	return $wpdb->get_row($sql);
  	
  }
  
  
  
}
      
?>