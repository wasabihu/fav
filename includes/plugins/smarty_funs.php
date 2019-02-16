<?php



//創建Smarty對象
function createSmarty(){
	global $smarty;
	
/* 创建 Smarty 对象,初始化模版信息*/	//wasa03
//require(DIR_WS_INCLUDES . 'smarty/libs/Smarty.class.php');

if(IS_LOCAL){
	$path= ROOT_PATH.'templates/compiled';
}else{
	$path= ROOT_PATH.'templates/compiled';
	
// 	$path="saemc://templates_c";//使用MC Wrapper
// 	mkdir($path);
}

//$smarty = new Smarty();
//$smarty->config_dir		= DIR_WS_INCLUDES.'smarty/configs';
//$smarty->template_dir   = ROOT_PATH.'templates/default';	//設置模版目錄
//$smarty->compile_dir    = $path;	//SAE_TMP_PATH;	//設置模版緩存目錄


# smarty
require ROOT_PATH.'lib/smarty/Smarty.class.php';
$smarty = new Smarty();

$smarty->template_dir   = ROOT_PATH.'templates/default';	//設置模版目錄
$smarty->compile_dir    = $path;	//SAE_TMP_PATH;	//設置模版緩存目錄

}


function smartyIsCached($page_path,$cache_id=''){
	global $smarty;
	
	if(empty($cache_id))
		$is_cached = $smarty->is_cached($page_path);	//檢測是否緩存
	else	
		$is_cached = $smarty->is_cached($page_path,$cache_id);	//檢測是否緩存
		
	return 	$is_cached;
}

function setSmartyVar($name,$var){
	global $smarty;
	
	$smarty->clear_assign($name);
	$smarty->assign($name,$var);
}


?>