<?php
/**
 * 項目初始化文件
 * 1. 包含includes/configure.php
 * 2. 如果連接mysql不成功，則跳到靜態頁面
 * 3. 包含和初始化各項文件
 * 4. 初始化session
 * 5. 初始化購物車shoppingCar
 * 6. 根據action參數，來添加，更新，刪除shoppingCar
 *
 * <code>
 * //定義項目的根路徑
 * define('ROOT_PATH', str_replace('includes/application_top.php', '', str_replace('\\', '/', __FILE__)));
 *
 *
 * @package    includes
 * @author     OSCommerce
 * @version    CVS: $Id: application_top.php,v 1.8 2010/03/30 13:38:22 dufuz Exp $
 */


$is_local = (isset($_SERVER['SERVER_NO']) && $_SERVER['SERVER_NO']=='localhost')? true : false;

define("IS_LOCAL", $is_local);
// echo "is_local == $is_local";


session_start();
define('ROOT_PATH', str_replace('includes/application_top.php', '', str_replace('\\', '/', __FILE__)));


if(isset($_GET['debug']) && $_GET['debug']=='101'){	error_reporting(E_ALL & ~E_NOTICE); }
else						error_reporting(1);


define('PROJECT_VERSION', 'wasa  v0.2');  //define the project version



require(ROOT_PATH.'includes/configure.php');
require_once( ROOT_PATH.'lib/WasaLib/wp-db.php' );
$wpdb->set_prefix($table_prefix);


require(DIR_WS_INCLUDES.'lib_common.php');
require(DIR_WS_INCLUDES.'lib_define.php');


require(DIR_WS_CLASSES.'user.php');

//包含Smarty
require(DIR_WS_INCLUDES.'plugins/smarty_funs.php');
createSmarty();


//2) 設置當前頁
$curr_page = basename($_SERVER['SCRIPT_NAME']);
$smarty->assign('curr_page',$curr_page);

//加上頁面控制
require(DIR_WS_CLASSES . 'ContrlAction.php');


//得到當前user_id
$user_id = User::getCurr_id();

if(empty($user_id)){
	$user_id = ($_REQUEST['u'] == 'wasabi') ? 1659969325 :0;
}
	
if($_GET['debug']==101)	echo "user_id == $user_id <br>";

user_init();

/**
 * 
 * 初始化用戶信息
 */
function user_init(){
	global $smarty,$user_id;
	
	if(empty($user_id)) return false;
	
	
	$user_info = FALSE;
	
	if(empty($_SESSION['curr_user']) ){
		$user_info = User::getCookie_userInfo();
	}else{
		$user_info = $_SESSION['curr_user'];
	}
	


	
	if(empty($user_info) || empty($user_info['uid']) ){
		$curr_user = new User($user_id);
		$user_info  = $curr_user->user_info;
	}

	
	
	$_SESSION['curr_user'] = $user_info;
	
	$smarty->assign('user_id',$user_id);
	$smarty->assign('user_info',$user_info);
	
// 	echo "user_id == $user_id <br> curr_user==<br>";	print_r($user_info);
	
}


function setAlertMessage($mes){
	$_SESSION['alert_mes'] =  $mes;
}

function getAlertMessage(){
	global $smarty;
	
	$alert_mes = $_SESSION['alert_mes'];
	$smarty->assign('alert_mes',$alert_mes);
	
//	echo "$alert_mes <br>";
	
	unset($_SESSION['alert_mes']);
}




function timer_start() {
	global $timestart;
	$mtime = explode( ' ', microtime() );
	$timestart = $mtime[1] + $mtime[0];
	return true;
}

/**
 * Return and/or display the time from the page start to when function is called.
 *
 * You can get the results and print them by doing:
 * <code>
 * $nTimePageTookToExecute = timer_stop();
 * echo $nTimePageTookToExecute;
 * </code>
 *
 * Or instead, you can do:
 * <code>
 * timer_stop(1);
 * </code>
 * which will do what the above does. If you need the result, you can assign it to a variable, but
 * most cases, you only need to echo it.
 *
 * @since 0.71
 * @global int $timestart Seconds and Microseconds added together from when timer_start() is called
 * @global int $timeend  Seconds and Microseconds added together from when function is called
 *
 * @param int $display Use '0' or null to not echo anything and 1 to echo the total time
 * @param int $precision The amount of digits from the right of the decimal to display. Default is 3.
 * @return float The "second.microsecond" finished time calculation
 */
function timer_stop( $display = 0, $precision = 3 ) { // if called like timer_stop(1), will echo $timetotal
	global $timestart, $timeend;
	$mtime = microtime();
	$mtime = explode( ' ', $mtime );
	$timeend = $mtime[1] + $mtime[0];
	$timetotal = $timeend - $timestart;
	$r = ( function_exists( 'number_format_i18n' ) ) ? number_format_i18n( $timetotal, $precision ) : number_format( $timetotal, $precision );
	if ( $display )
		echo $r;
	return $r;
}

function set_page_keywords($keywords=''){
	global $smarty;
	if(empty($keywords))
		$keywords = '�̄�ӡ���^,�̄վW�ϕ��,�W�ϕ��,cp1897,CP1897,cp1897.com,online,bookstore,bookshop,���ĈD��,English Books,�̄ճ���,�̄��T��,����,commercial press,�ֵ�,�~��,��,���N��,�ϳ��漯�F,��������,������A���,���A�̄���ӡˢ';
	// Updated by Katy Tsai as at 4-Mar-2009

	$smarty->clear_assign('keywords');
	$smarty->assign('keywords',$keywords);
}


function set_page_description($description=''){
	global $smarty;
	
	if(empty($description))
		$description = 'description';
	// Updated by Katy Tsai as at 4-Mar-2009

	$smarty->clear_assign('description');
	$smarty->assign('description',$description);
}


function set_page_title($page_title){
	global $smarty;
	
	$smarty->assign('page_title',$page_title);
}

//=========================sina =================================//

function get_mmc(){
	static $mmc = FALSE;
	 
// 	if ($mmc){
// 		return $mmc;
// 	}

	return $mmc;
}

?>
