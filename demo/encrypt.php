<?php
/**

* 加解密字符

*

* @param  string $string 需要加/解密的串.

* @param  string $operation 加/解密模式. ENCODE | DECODE

* @return array

*/

function encrypt($crypt,$mode='ENCODE'){

//    $key = 'coderbol';//任意8位字符串
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


/**
 +-----------------------------------------------------
 * Mcrypt 加密/解密
 * @param String $date 要加密和解密的数据
 * @param String $mode encode 默认为加密/decode 为解密
 * @return String
 * @author zxing@97md.net Mon Sep 14 22:59:28 CST 2009
 +-----------------------------------------------------
 * @example
 */
function ZxingCrypt($date,$mode = 'encode'){
    $key = md5('zxing');//用MD5哈希生成一个密钥，注意加密和解密的密钥必须统一
    if ($mode == 'decode'){
        $date = base64_decode($date);
    }
    if (function_exists('mcrypt_create_iv')){
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    }
    if (isset($iv) && $mode == 'encode'){
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $date, MCRYPT_MODE_ECB, $iv);
    }elseif (isset($iv) && $mode == 'decode'){
        $passcrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $date, MCRYPT_MODE_ECB, $iv);
    }
    if ($mode == 'encode'){
        $passcrypt = base64_encode($passcrypt);
    }
    return $passcrypt;
}



//===================================================//
$str = 'wasabi.net@gmail.com 你好吗，我是Wasabi,这是一个测试，哈哈~！';

$enstr = encrypt($str,'ENCODE');
echo  $enstr.'<br>';

echo encrypt($enstr , 'DECODE');

echo "<br>===================================================<br><br>";


$pwd = 'wasabi.net@gmail.com 你好吗，我是Wasabi,这是一个测试，哈哈~！';
$en_pwd = ZxingCrypt($pwd);
$de_pwd = ZxingCrypt($en_pwd , 'decode');

echo "$pwd  encode: $en_pwd 	, decode: $de_pwd <br>";
