<?php

/**
 *  公用函数库
 */


/**
 * 截取UTF-8编码下字符串的函数
 *
 * @param   string      $str        被截取的字符串
 * @param   int         $length     截取的长度
 * @param   bool        $append     是否附加省略号
 *
 * @return  string
 */
function sub_str($str, $length = 0, $append = true){
	$str = trim($str);
	$strlength = strlen($str);

	if ($length == 0 || $length >= $strlength)
	{
		return $str;
	}
	elseif ($length < 0)
	{
		$length = $strlength + $length;
		if ($length < 0)
		{
			$length = $strlength;
		}
	}

	if (function_exists('mb_substr')){
		$newstr = mb_substr($str, 0, $length, 'UTF-8');
	}
	elseif (function_exists('iconv_substr')){
		$newstr = iconv_substr($str, 0, $length, 'UTF-8');
	}
	else{
		$newstr = trim_right(substr($str, 0, $length));
	}

	if ($append && $str != $newstr){
		$newstr .= '...';
	}

	return $newstr;
}

/**
 * 去除字符串右侧可能出现的乱码
 *
 * @param   string      $str        字符串
 *
 * @return  string
 */
function trim_right($str)
{
	$length = strlen(preg_replace('/[\x00-\x7F]+/', '', $str)) % 3;

	if ($length > 0)
	{
		$str = substr($str, 0, 0 - $length);
	}

	return $str;
}

/**
 * 计算字符串的长度（汉字按照两个字符计算）
 *
 * @param   string      $str        字符串
 *
 * @return  int
 */
function str_len($str)
{
	$length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));

	if ($length)
	{
		return strlen($str) - $length + intval($length / 3) * 2;
	}
	else
	{
		return strlen($str);
	}
}

/**
 * 获得用户操作系统的换行符
 *
 * @access  public
 * @return  string
 */
function get_crlf()
{
	/* LF (Line Feed, 0x0A, \N) 和 CR(Carriage Return, 0x0D, \R) */
	if (stristr($_SERVER['HTTP_USER_AGENT'], 'Win'))
	{
		$the_crlf = '\r\n';
	}
	elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Mac'))
	{
		$the_crlf = '\r'; // for old MAC OS
	}
	else
	{
		$the_crlf = '\n';
	}

	return $the_crlf;
}

/**
 * 创建像这样的查询: "IN('a','b')";
 *
 * @access   public
 * @param    mix      $item_list      列表数组或字符串
 * @param    string   $field_name     字段名称
 * @author   Xuan Yan
 *
 * @return   void
 */
function db_create_in($item_list, $field_name = ''){
	if (empty($item_list))
	{
		return $field_name . " IN ('') ";
	}
	else{
		if (!is_array($item_list)){
			$item_list = explode(',', $item_list);
		}

		$item_list = array_unique($item_list);
		$item_list_tmp = '';
		foreach ($item_list AS $item)
		{
			if ($item !== '')
			{
				$item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
			}
		}

		if (empty($item_list_tmp))
		{
			return $field_name . " IN ('') ";
		}
		else
		{
			return $field_name . ' IN (' . $item_list_tmp . ') ';
		}
	}
}

/**
 * 创建像这样的字符串: "a,b"
 *
 * @access   public
 * @param    mix      $item_list      列表数组或字符串
 * @author   Xuan Yan
 *
 * @return   void
 */
function str_create($item_list){
	$field_name = '';
	if (empty($item_list)){
		return $field_name;
	} else{
		//如果不是數組
		if (!is_array($item_list))  $item_list = explode(',', $item_list);

		$item_list = array_unique($item_list);	//去除重復

		$item_list_tmp = '';
		foreach ($item_list AS $item){
			if ($item !== '')
			$item_list_tmp .= $item_list_tmp ? ",$item" : "$item";
		}


		if (empty($item_list_tmp))
			return $field_name;
		else
			return $field_name . $item_list_tmp;
	}
}

/**
 * 获得用户的真实IP地址
 *
 * @access  public
 * @return  string
 */
function real_ip(){
	static $realip = NULL;

	if ($realip !== NULL)
	{
		return $realip;
	}

	if (isset($_SERVER))
	{
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

			/* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
			foreach ($arr AS $ip)
			{
				$ip = trim($ip);

				if ($ip != 'unknown')
				{
					$realip = $ip;

					break;
				}
			}
		}
		elseif (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else
		{
			if (isset($_SERVER['REMOTE_ADDR']))
			{
				$realip = $_SERVER['REMOTE_ADDR'];
			}
			else
			{
				$realip = '0.0.0.0';
			}
		}
	}
	else
	{
		if (getenv('HTTP_X_FORWARDED_FOR'))
		{
			$realip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_CLIENT_IP'))
		{
			$realip = getenv('HTTP_CLIENT_IP');
		}
		else
		{
			$realip = getenv('REMOTE_ADDR');
		}
	}

	preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
	$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

	return $realip;
}

/**
 * 验证输入的邮件地址是否合法
 *
 * @access  public
 * @param   string      $email      需要验证的邮件地址
 *
 * @return bool
 */
function is_email($user_email){
  
	$chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
	if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
	{
		if (preg_match($chars, $user_email))
		{
			return true;
		} else
		{
			return false;
		}
	} else
	{
		return false;
	}
}

/**
 * 检查是否为一个合法的时间格式
 *
 * @access  public
 * @param   string  $time
 * @return  void
 */
function is_time($time){
	$pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';

	return preg_match($pattern, $time);
}


///////////////////////////////////////////////////////////////////////////////////////////////

function getLinks($item_id=1){
	global $wpdb;
	$sql = "SELECT * from link where item_id=$item_id order by seq"; //order by seq
	
	
// 	echo "$sql <br>";
	
	$data =	$wpdb->get_results($sql);
	
	if($_GET['debug']==101){
		echo "$sql <br>";
		print_r($data);
	}	
	
	return $data;
}


function getItemsBy_uid($uid){
	global $wpdb;
		
	if(empty($uid) || !is_numeric($uid) )	$uid=DEF_UID;
		
	$sql = "SELECT * from item where uid=$uid order by sort";
	


	return $wpdb->get_results($sql);
}

function getTempItems(){
	global $wpdb;
		

	$sql = "SELECT * from item where is_template=1 order by sort";

	return $wpdb->get_results($sql);
}

function generatedTemplate($uid){
	global $wpdb;
	foreach(getTempItems() as $item){
		//1) insert Item
		
		$item_id = $item['id'];
		
		$item['uid']= $uid;
		$item['is_template']=0;
		unset($item['id']);	
	
		$wpdb->insert('item', $item);
	
		$new_item_id= $wpdb->insert_id;
		
		
		foreach(getLinks($item_id) as $link){
			unset($link['id']);
			$link['item_id'] = $new_item_id;
			
			$wpdb->insert('link', $link);
		}
		
		
	} 
	

}



function getLinksBy_uid($uid){
	global $wpdb;

	$list =array();
	
	if(empty($uid) || !is_numeric($uid) )	$uid=DEF_UID;
	
	$sql ="select i.title,i.sort,l.item_id,l.description,l.seq  FROM item i,link l ". 
		" where i.id=l.item_id AND i.uid=$uid order by i.sort,l.seq";
	
	 $link_list = $wpdb->get_results($sql);
	 
	 foreach ($link_list as $data){
	 	$item_id = $data['item_id'];
	 	
	 	$list[$item_id][] = $data;
	 }
	 
	 return $list;
}




////直接跳轉到另一個頁面或網站
// Redirect to another page or site
function tep_redirect($url) {
	unset($_SESSION['last_session_request']);
/*

	if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) {
		tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
	}

	if ( (ENABLE_SSL == true) && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page
		if (substr($url, 0, strlen(HTTP_SERVER)) == HTTP_SERVER) { // NONSSL url
			$url = HTTPS_SERVER . substr($url, strlen(HTTP_SERVER)); // Change it to SSL
		}
	}
	*/
	header('Location: '. $url);
	exit();
}

/**

* 加解密字符

*

* @param  string $string 需要加/解密的串.

* @param  string $operation 加/解密模式. ENCODE | DECODE

* @return array

*/

function wa_encrypt($crypt,$key='spwdwasa', $mode='ENCODE'){

	if (empty($key) || strlen($key)!=8)
    	$key = 'spwdwasa';//任意8位字符串

    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_ECB),MCRYPT_RAND);

    // 加密
    if( 'ENCODE' == $mode ){   
        $passcrypt = mcrypt_encrypt(MCRYPT_DES ,$key, $crypt, MCRYPT_MODE_ECB, $iv);
        
        $str =  str_replace( array('=','/','+'),array('','-','_'), base64_encode($passcrypt) );
      
    }else{

       $decoded = base64_decode( str_replace(array('-','_'), array('/','+'), $crypt ) );

       $str = mcrypt_decrypt(MCRYPT_DES ,$key, $decoded, MCRYPT_MODE_ECB, $iv); 

    }  

    return $str;

}
?>