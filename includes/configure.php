<?php


define('PRODUCTION', true);   //如果值為true，即生產環境
define('DEVELOPMENT', false);  //如果值為 true 並且PRODUCTION為false,即生產環境




if(IS_LOCAL){
  define('HTTP_SERVER', 'http://127.0.0.1');
  define('HTTPS_SERVER', 'http://127.0.0.1');
  
    define('ENABLE_SSL', true);
  
	define('HTTP_COOKIE_DOMAIN', 'wasa.sinaapp.com');
	define('HTTPS_COOKIE_DOMAIN', 'wasa.sinaapp.com');	
  
  define('DB_USER', 'root');	/** 该数据库的用户名 */
  define('DB_PASSWORD', '123456');	/** 该用户的密码 */
  
	define('DB_NAME', 'wasa'); //wf_demo
    define('DB_HOST', 'localhost');		/** 数据库的主机名（一般情况下不需要改动本项） */
    
    /** 数据库在创建表时使用的字符集（一般情况下不需要改动本项） */
    define('DB_CHARSET', 'utf8');//utf8
	
    /** 数据库的整理类型（如果您不清楚本设置，请不要改动本项）*/
    define('DB_COLLATE', '');
    $table_prefix  = '';
}
else{
	define('HTTP_SERVER', 'http://'.$_SERVER['HTTP_HOST']);
	define('HTTPS_SERVER', 'http://'.$_SERVER['HTTP_HOST']);
	
	define('ENABLE_SSL', true);
	define('HTTP_COOKIE_DOMAIN', 'wasa.sinaapp.com');
	define('HTTPS_COOKIE_DOMAIN', 'wasa.sinaapp.com');	
  
	define('DB_USER', 'ryfgl2pb7w');	/** 该数据库的用户名 */
	define('DB_PASSWORD', 'Kenbli9394');	/** 该用户的密码 */
  
	define('DB_NAME', 'ryfgl2pb7w'); //wf_demo
    define('DB_HOST', 'rdsu1st5lk671p82d0bz.mysql.rds.aliyuncs.com'.':3306');		/** 数据库的主机名（一般情况下不需要改动本项） */
    
    /** 数据库在创建表时使用的字符集（一般情况下不需要改动本项） */
    define('DB_CHARSET', 'utf8');//utf8
    /** 数据库的整理类型（如果您不清楚本设置，请不要改动本项）*/
    define('DB_COLLATE', '');
    $table_prefix  = '';
}
  

/************** 公用部分 ***************************/
define('HTTP_COOKIE_PATH', '/');
define('HTTPS_COOKIE_PATH', '/');
define('DIR_WS_HTTP_CATALOG', '/');
define('DIR_WS_HTTPS_CATALOG', '/');
define('DIR_WS_IMAGES', ROOT_PATH.'image/');


define('DIR_WS_INCLUDES', ROOT_PATH.'includes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');


  //define('DATA_ROOT_PATH', '/data/');
  /*
  define('HTTP_SERVER', 'http://www.juandongxi.com');
  define('HTTPS_SERVER', 'https://www.juandongxi.com');
  */
  
//  define('HTTP_SERVER', 'http://www.juandongxi.com');
//  define('HTTPS_SERVER', 'https://www.juandongxi.com');
  


    
    
//======= 不會修改=========================//

  define('SMARTY_CACHE',2);	
  define('SMARTY_CACHE_LIFETIME',3600*4);	//smarty緩存時間設置
  define('PEAR_CACHE_LIFETIME',3600*24);	//pear緩存時間設置
  
  define('HTTP_HOME', HTTP_SERVER.'/');
  define('HTTPS_HOME', HTTPS_SERVER.'/');
  define('CHARSET', 'UTF-8');
?>
