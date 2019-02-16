<?php

/* 创建 Smarty 对象,初始化模版信息*/	//wasa03
require(DIR_WS_INCLUDES . 'smarty/libs/Smarty.class.php');

/*定義模版path*/

if(IS_LOCAL){
	$path= ROOT_PATH.'templates/compiled';
}else{
	$path="saemc://templates_c";//使用MC Wrapper
	mkdir($path);
}

	  
$smarty = new Smarty();

/*      
      $smarty->template_dir = "./templates"; 
      $smarty->compile_dir = $path; //设置编译目录
      $smarty->assign("str1", "Hello,Smarty."); 
      //编译并显示位于./templates下的index.tpl模板
      @$smarty->display("sample.tpl");
	 */ 
	 
//$smarty->cache_dir = ROOT_PATH.'templates/cache';//設置緩存目錄
//$smarty->cache_lifetime = 3600 * 24;	//設置模版緩存時間60秒
//$smarty->compile_check = true;	//如果為true,那么缓存文件总是会被重新生成，也就实际上关闭了缓存

/* 開啟此功能是方便開發和調試,系統發布時請將force_compile設置為：false。*/
/*$smarty->force_compile = true;*/

$smarty->config_dir		= DIR_WS_INCLUDES.'smarty/configs';
$smarty->template_dir   = ROOT_PATH.'templates/default';	//設置模版目錄

$smarty->compile_dir    = $path;	//SAE_TMP_PATH;	//設置模版緩存目錄

//$smarty->caching =true;



     
	  
	  
/*
require(DIR_WS_INCLUDES.'lib_main.php');
require(DIR_WS_INCLUDES.'lib_time.php');
require(DIR_WS_INCLUDES.'lib_common.php');
require(DIR_WS_INCLUDES.'lib_define.php');
*/

?>
