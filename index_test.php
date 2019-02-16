<?php
  require('includes/application_top.php');
  
  $contrl = new ContrlAction();
  $contrl->dipatchAction();


/*
if($action=='edit_link'){	edit_linkAction(); }
elseif($action=='add_item'){	add_itemAction(); }
elseif($action=='delete_link'){	delete_linkAction(); }
elseif($action=='logout'){	logoutAction(); }
else{ indexAction();}
*/


function indexAction(){
	global $smarty,$user_id,$contrl;
	
	sae_xhprof_start();
	
	$uid='';
//	 echo "act== $contrl->action <br>";
	
	
	if($user_id){
	 	$uid=$user_id;
	 	$item_arr =  getItemsBy_uid($uid);
	 	
	 	$smarty->assign('is_edit',true);
	}
	
	else{
		$is_redirect=false;
		if($contrl->action=='inv')	$is_redirect = true;
		

		initSina($is_redirect);
		$item_arr =  getTempItems();
	}

	
	
  $item_list  = array();
  
  $cache_key = "item_list_{$uid}";
	
  $mmc = memcache_init();
  
  
     
   $item_list = memcache_get($mmc,$cache_key);
   
   if(empty($item_list)){
   		$count=0;
	  	foreach ($item_arr as $data){
		 	$item_id = $data['id'];
		 	
		 	$data['link_list'] = getLinks($item_id);
		 	
		 	
		 	$item_list[] = $data;
		 	
		 	$count++;
	 }

   	 	memcache_set($mmc,$cache_key,$item_list);
   }
   else{
   		$count = sizeof($item_list);
//   		echo "is memcached ! <br>";
   }


  $smarty->assign('item_length',$count);
  
  $smarty->assign('item_list',$item_list);
  
//  if($smarty->is_cached('index.html'))	echo 'cached....~<br>';
  $smarty->display('index.html');
  
  sae_xhprof_end();
}


function initSina($is_redirect=false){
	global $smarty;
	
	if (! class_exists('WeiboOAuth')) return false;
	
	
	$o = new WeiboOAuth( WB_AKEY , WB_SKEY );
	$keys = $o->getRequestToken();

	
	$callback = HTTP_HOME .'callback.php';
	
//	if( strpos( $_SERVER['SCRIPT_URI'] , 'index.php' ) === false )
//		$callback =  $_SERVER['SCRIPT_URI'].'/callback.php';
//	else	
//		$callback =  str_replace( 'index.php' , 'callback.php' , $_SERVER['SCRIPT_URI'] );


	$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callback );

	$_SESSION['keys'] = $keys;
	
	
	if($is_redirect) 	$smarty->assign('is_inv','1');
//		tep_redirect($aurl);	
	
	$smarty->assign('loginUrl',$aurl);	
}


//function getSinaLoginURLAction(){
//	
//	if (! class_exists('WeiboOAuth')) return false;
//	
//	$o = new WeiboOAuth( WB_AKEY , WB_SKEY );
//	$keys = $o->getRequestToken();
//	
//	$callback = HTTP_HOME .'callback.php';
//	
//	$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callback );
//	
//	$_SESSION['keys'] = $keys;
//	
//	tep_redirect($aurl);
//}

//来至新浪的邀请
function sinaInviteAction(){
	initSina(true);
	
}

function logoutAction(){

//	die('AAAAAAAAAAAAA');
	
	User::logout();
	
	tep_redirect('index.php');

}

function add_itemAction(){
	global $wpdb,$user_id;
	
	if(empty($user_id))	echo 0;
	
	$data['uid'] = $user_id;
	
	$data['title'] = $wpdb->_escape($_POST['title']);
	$data['description'] = $wpdb->_escape($_POST['description']);
	$data['sort'] = $wpdb->_escape($_POST['sort']);
	
	if(empty($data['sort']))	$data['sort'] =80;
	
	
	$wpdb->insert('item', $data);
	
	
	echo $wpdb->insert_id;

}

function delete_linkAction(){
	global $wpdb;
	
	$linke_id = $wpdb->_escape($_POST['link_id']);
	
	if(empty($linke_id))	echo 0;
	
	$sql ="delete from link where id=$linke_id";
	
	$wpdb->query($sql);
	
	echo 1;
}
function edit_linkAction(){
	global $wpdb;
	
	$data =array();
	
	$where['id'] = $wpdb->_escape($_POST['link_id']);
	

	$data['name'] = $wpdb->_escape($_POST['name']);
	$data['href'] = $wpdb->_escape($_POST['href']);
	$data['description'] = $wpdb->_escape($_POST['description']);
	$data['seq'] = $wpdb->_escape($_POST['seq']);
	
	if(empty($data['seq']) || !is_numeric($data['seq'])) $data['seq']=188;
	
	//新增
	if(empty($where['id'])){
		$data['item_id'] = $wpdb->_escape($_POST['item_id']);
		$wpdb->insert('link', $data);
		
		echo "更新成功！";
	
	}
	else{	//修改
	
		$data['item_id'] = $wpdb->_escape($_POST['item_id']);
		$wpdb->update('link', $data, $where);
	}
//	echo $where['id'];
}

function update_weiboAction(){
	global $smarty,$user_id;
	
	$user = $_SESSION['curr_user'];
	
	if(empty($user['oauth_token']))	$user = new User($user_id);	

	//echo "aa =".$_REQUEST['text'] .'<br>';
	
//	print_r($user);die;	

	$c = new WeiboClient( WB_AKEY, WB_SKEY,$user['oauth_token'] , $user['oauth_token_secret']);
	$ms  = $c->home_timeline(); // done
	$me = $c->verify_credentials();

	$rr = $c->update( $_REQUEST['text'] );	

	
	echo "发送完成" ; 
}

function commentAction(){
	global $smarty,$user_id;
	
	if(empty($_POST['text'])){
		echo "內容為空，提交不成功！";
		return false;
	}
	
	$user = $_SESSION['curr_user'];
	
	
	if(empty($user['oauth_token']))	$user = new User($user_id);	


	$c = new WeiboClient( WB_AKEY, WB_SKEY,$user['oauth_token'] , $user['oauth_token_secret']);
	$ms  = $c->home_timeline(); // done
	$me = $c->verify_credentials();
	
//	$id = '3344259161980413';
	$mid = '3344259161980413';
	
	//對mid 所在微博發表一條評論。
	$msg = $c->send_comment($mid,$_REQUEST['text'],null);
	
	
	echo "谢谢你提出宝贵意见！"; 
}
  
?>
