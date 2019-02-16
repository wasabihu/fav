<?php

require('includes/application_top.php');
include_once( 'weibov2/config.php' );
include_once( 'weibov2/saetv2.ex.class.php' );

/*
 * V2 Array ( [id] => 1659969325 
 * [idstr] => 1659969325 [screen_name] => WasabiHu [name] => WasabiHu 
 * [province] => 44 [city] => 1 [location] => 广东 广州 [description] => 莫将容易得，便作等闲看。 
 * [url] => http://wasabi.javaeye.com/ [profile_image_url] => http://tp2.sinaimg.cn/1659969325/50/40001736436/1 
 * [profile_url] => wasa [domain] => wasa [weihao] => [gender] => m 
 * [followers_count] => 943 [friends_count] => 881 [statuses_count] => 6961 
 * [favourites_count] => 694 [created_at] => Tue Nov 10 23:00:52 +0800 2009 [following] => 
 * [allow_all_act_msg] => [geo_enabled] => 1 [verified] => [verified_type] => 220 [remark] => 
 * [status] => Array ( 
 * 		[created_at] => Thu Sep 20 15:26:10 +0800 2012 [id] => 3492355800059046 [mid] => 3492355800059046 [idstr] => 3492355800059046 
 * 		[text] => [lxhx咻3] [lxhx咻7]//@ZAKER橱窗: 噗~~ZAKER君高山流水，下里巴人，都hold地住~[笑哈哈] [source] => 新浪微博 [favorited] =>
 * 		[truncated] => [in_reply_to_status_id] => [in_reply_to_user_id] => [in_reply_to_screen_name] => [geo] => 
 * 		[retweeted_status] => Array ( 
 * 				[created_at] => Thu Sep 20 14:55:09 +0800 2012 [id] => 3492347998748646 [mid] => 3492347998748646 [idstr] => 3492347998748646 
 * 				[text] => ZAKER广告时间！普通青年、文艺青年、草榴青年…请看ZAKER内容订阅那点事儿~欢迎对号入座哦~嘿嘿。 [source] => ZAKER [favorited] =>
 * 				 [truncated] => [in_reply_to_status_id] => [in_reply_to_user_id] => [in_reply_to_screen_name] => [thumbnail_pic] => http://ww3.sinaimg.cn/thumbnail/7128be06jw1dx2zqkq6lyj.jpg [bmiddle_pic] => http://ww3.sinaimg.cn/bmiddle/7128be06jw1dx2zqkq6lyj.jpg [original_pic] => http://ww3.sinaimg.cn/large/7128be06jw1dx2zqkq6lyj.jpg [geo] => [user] => Array ( [id] => 1898495494 [idstr] => 1898495494 [screen_name] => ZAKER [name] => ZAKER [province] => 11 [city] => 1000 [location] => 北京 [description] => ZAKER，最流行的阅读软件： iPhone V2.4 下载地址:http://t.cn/arJsZ8 iPad V2.3.1... [url] => http://www.myzaker.com [profile_image_url] => http://tp3.sinaimg.cn/1898495494/50/39997977901/1 [profile_url] => zakerzaker [domain] => zakerzaker [weihao] => [gender] => m [followers_count] => 107440 [friends_count] => 1113 [statuses_count] => 4955 [favourites_count] => 31 [created_at] => Sat Dec 18 19:31:52 +0800 2010 [following] => [allow_all_act_msg] => 1 [geo_enabled] => 1 [verified] => 1 [verified_type] => 2 [allow_all_comment] => 1 [avatar_large] => http://tp3.sinaimg.cn/1898495494/180/39997977901/1 [verified_reason] => ZAKER 官方微博 [follow_me] => [online_status] => 0 [bi_followers_count] => 797 [lang] => zh-cn ) [reposts_count] => 0 [comments_count] => 0 [attitudes_count] => 0 [mlevel] => 0 [visible] => Array ( [type] => 0 [list_id] => 0 ) ) [reposts_count] => 0 [comments_count] => 0 [attitudes_count] => 0 [mlevel] => 0 [visible] => Array ( [type] => 0 [list_id] => 0 ) ) [allow_all_comment] => [avatar_large] => http://tp2.sinaimg.cn/1659969325/180/40001736436/1 [verified_reason] => [follow_me] => [online_status] => 1 [bi_followers_count] => 395 [lang] => zh-cn )

*/

	
	$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

	
	if (isset($_REQUEST['code'])) {
		
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}


	if ($token) {
		$_SESSION['token'] = $token;
		setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
		

		
		//=================================================================================================================//
		
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		
// 		$ms  = $c->home_timeline(); // done
// 		$uid_get = $c->get_uid();
// 		$uid = $uid_get['uid'];	

		$uid = $token['uid'];
		$userinfo = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
		
		

// 		print_r($token);
// 		print_r($userinfo);
// 		die('AAAAAAAA');
	
	if(empty($userinfo['domain']))	$userinfo['domain'] = $userinfo['id'];
	
	$user_email = $userinfo['domain'].'@weibo.com';			//加上新浪微博特有的後綴。
	$scid =$userinfo['id'];
//	$avatars_path = 'http://tp3.sinaimg.cn/'.$scid.'/180/1.jpg';

	$userdata = array(
			'uid'=>$scid,'access_type'=>1,'nickname'=>$userinfo['name'],'domain'=>$userinfo['domain'],
			'location'=>$userinfo['location'],'province'=>$userinfo['province'],'city'=>$userinfo['city'],
			'gender'=>$userinfo['gender'],'description'=>$userinfo['description'],
			'access_token'=>$_SESSION['token']['access_token'],
			'avatars_path'=>$userinfo['profile_image_url'],
			'user_email'=>$user_email, 'passwordaes' => md5($scid),	//根据新浪的userInfo_id来生成密码	
			);
		
		
//	print_r($userinfo);	echo "<br><br>";
//	print_r($userdata);

			
	//如果數據不存在，则添加新用户
	if(! User::check_userEmail_exist($user_email)){
		//1) 插入用户信息到数据库
		$uid = User::insert($userdata);
		
		//2) 生成模板数据库
		generatedTemplate($uid);
		
		//3) 发一条微博
//		$text = "真方便，双击就能修改，我正在使用属于我的微博收藏夹，可以随意的添加你喜欢的网址，你也来试试吧。  http://wasa.sinaapp.com/?act=inv";
//		$rr = $c->update($text);	
			
	}
	else{
	
		$updata_data = array(
			'nickname'=>$userinfo['name'],'domain'=>$userinfo['domain'],'description'=>$userinfo['description'],
			'access_token'=>$_SESSION['token']['access_token'],
			'avatars_path'=>$userinfo['profile_image_url']	
			);
			
		
		//更新用戶信息
		User::update($updata_data, $scid);
	}


	//記錄登錄狀態
	if($user_data = User::share_login($user_email)){
		
			User::setUserSession($user_data);
	}
	
	//得到并存下user_id
	//$user_id = User::getCurr_id();
	
	if($_REQUEST['is_tb']=='1')
		echo '<script type="text/javascript">parent.location.reload(true);</script>';	// tb_remove();parent.location.reload(true); 
	else
		tep_redirect('index.php');		
		
	}
	
	
	else{
		
		echo "授权失败。";
	}
	
	
//	die();
//	
//
//	$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;
//	$_SESSION['last_key'] = $last_key;
//	
//	$tok = $_SESSION['last_key'];
//	
//	// $c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
//	
//	
//	//$ms  = $c->home_timeline(); // done
//	$userinfo= $c->verify_credentials();
//	
//
//
//	if(empty($userinfo['domain']))	$userinfo['domain'] = $userinfo['id'];
//	
//	$user_email = $userinfo['domain'].'@weibo.com';	//加上新浪微博特有的後綴。
//	$scid =$userinfo['id'];
////	$avatars_path = 'http://tp3.sinaimg.cn/'.$scid.'/180/1.jpg';
//	
//	
//	
//	
//	$userdata = array(
//			'uid'=>$scid,'access_type'=>1,'nickname'=>$userinfo['name'],'domain'=>$userinfo['domain'],
//			'location'=>$userinfo['location'],'province'=>$userinfo['province'],'city'=>$userinfo['city'],
//			'gender'=>$userinfo['gender'],'description'=>$userinfo['description'],
//			'oauth_token'=>$_SESSION['last_key']['oauth_token'],'oauth_token_secret'=>$_SESSION['last_key']['oauth_token_secret'],
//			'avatars_path'=>$userinfo['profile_image_url'],
//			'user_email'=>$user_email, 'passwordaes' => md5($scid),	//根据新浪的userInfo_id来生成密码	
//			);
//	
//	//如果數據不存在，则添加新用户
//	if(! User::check_userEmail_exist($user_email)){
//		//1) 插入用户信息到数据库
//		$uid = User::insert($userdata);
//		
//		//2) 生成模板数据库
//		generatedTemplate($uid);
//		
//		//3) 发一条微博
////		$text = "真方便，双击就能修改，我正在使用属于我的微博收藏夹，可以随意的添加你喜欢的网址，你也来试试吧。  http://wasa.sinaapp.com/?act=inv";
////		$rr = $c->update($text);	
//			
//	}
//	else{
//	
//		$updata_data = array(
//			'nickname'=>$userinfo['name'],'domain'=>$userinfo['domain'],'description'=>$userinfo['description'],
//			'oauth_token'=>$_SESSION['last_key']['oauth_token'],'oauth_token_secret'=>$_SESSION['last_key']['oauth_token_secret'],
//			'avatars_path'=>$userinfo['profile_image_url']	
//			);
//			
//		
//		//更新用戶信息
//		User::update($updata_data, $scid);
//	}
//
//
//	//記錄登錄狀態
//	if($user_data = User::share_login($user_email)){
//		
//			User::setUserSession($user_data);
//	}
//	
//	//得到并存下user_id
//	//$user_id = User::getCurr_id();
//	
//	if($_REQUEST['is_tb']=='1')
//		echo '<script type="text/javascript">parent.location.reload(true);</script>';	// tb_remove();parent.location.reload(true); 
//	else
//		tep_redirect('index.php');

?>

