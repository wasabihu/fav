<?php

//header('Content-Type: text/html; charset=utf-8');


$url = "http://u.myzaker.com/user/login_post";



$fields = array(
               'username'=>'2549407@qq.com' ,'password'=>'za469394' , 'auto_login'=>'1' );


	$f = new SaeFetchurl();
	
  	
        $f->setMethod('post');
  	$f->setPostData($fields);
  	
//$opt = array('redirect'=>true);      

       
       $f->setAllowRedirect(false);
                
       $ret = $f->fetch($url);
        
  

  
  
  $cookies = $f->responseCookies();
  
  
  
/*
  
   print_r($f);
   
   
*/

//   print_r($cookies);
  
  $url = "http://u.myzaker.com/share/index/";
  
   $f2 = new SaeFetchurl();
   
   $f2->setCookies($cookies);//设置COOKIES
   
   $f->setAllowRedirect(false);
   
   $ret = $f2->fetch($url);	
   
   
   if($f2->errno() == 0) echo "OK";
   else echo $f2->errmsg();
   
   echo $ret;