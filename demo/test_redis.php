<?php
header ( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header ( "Pragma: no-cache" );

//redis php 实例一 http://blog.51yip.com/cache/1439.html

$redis = new Redis();
$redis->connect('127.0.0.1',6379);



echo "ping: " . $redis->ping();

echo "<br>echo " . $redis->echo("hi wasabi");

$redis->set('x', 'a');
$redis->incr('x');
$err = $redis->getLastError();	// "ERR value is not an integer or out of range"\
echo "<br>err: $err";

$redis->clearLastError();
$err = $redis->getLastError();



//=== 得到全部的key ====//
$keys_list = $redis->keys('*');
echo "<br>KEYS :  <br>";	print_r($keys_list);

//=== 得到配置信息 ====//
$config_list =  $redis->config("GET", "*max-*-entries*");
echo "<br>config_list :  <br>";	print_r($config_list);

// $config_list =  $redis->config("GET", "*");
// echo "<br>config_list :  <br>";	print_r($config_list);


// === 选择数据库 ====//
// $redis->select(0);  // switch to DB 0
// $redis->set('x', '42'); // write 42 to x
// $redis->move('x', 1);   // move to DB 1
// $redis->select(1);  // switch to DB 1
// $redis->get('x');   // will return 42



// === 键值设置和删除 ====//
echo "<br><br>exists : foo :  " . $redis->exists('foo');

echo "<br>TYPE : foo :  " 		. $redis->type('foo');		//返回的是int ,string: Redis::REDIS_STRING set: Redis::REDIS_SET list: Redis::REDIS_LIST zset: Redis::REDIS_ZSET hash: Redis::REDIS_HASH other: Redis::REDIS_NOT_FOUND
echo "<br>TYPE : queue2 :  " 	. $redis->type('queue2');

echo "<br>GET : foo :  " 		. $redis->get('foo');
echo "<br>DEL :foo  :  " 		. $redis->del('foo');
echo "<br>SET :foo  :  " 		. $redis->set('foo' , 22);

echo "<br>INCR:foo  :    " 		. $redis->incr('foo');			//+1
echo "<br>INCRBY:foo  :  " 		. $redis->incrby('foo'  , 5);	//+5

echo "<br>DECR:foo  :    " 		. $redis->decr('foo' );	//-1
echo "<br>DECRBY:foo  :  " 		. $redis->decrby('foo',3 );	//-3

echo "<br>STRLEN: foo :"   . $redis->strlen('foo');

$redis->set('str_key:01' , 'Wasabi');
echo "<br>STRLEN: str_key:01 :"   . $redis->strlen('str_key:01');


// #用MSET一次储存多个值
$array_mset=array('first_key'=>'first_val',	'second_key'=>'second_val',	'third_key'=>'third_val');
$redis->mset($array_mset);

$array_mget=array('first_key','second_key','third_key');

print_r($redis->mget($array_mget)); #一次返回多个值 //array(3) { [0]=> string(9) "first_val" [1]=> string(10) "second_val" [2]=> string(9) "third_val" }


echo "<br><br>";


// === 散列类型 H ====//

$redis->hSet('car:01', 'key1', 'hello'); 
echo "<br>hget: car:01 :" . $redis->hGet('car:01', 'key1'); /* returns "hello" */

$redis->hSet('car:01', 'name', 'BMW');
$redis->hSet('car:01', 'color', 'Black');
$redis->hSet('car:01', 'price', '1000000');
$redis->hSet('car:01', 'date', date('Y-m-d H:i:s'));



// 判断字段是否存在
echo "<br>hexists: car:01_name  :" . $redis->hexists('car:01', 'name');
echo "<br>hexists: car:01_model :" . $redis->hexists('car:01', 'model'); 

// 当字段不存在时赋值
echo "<br>hsetnx: car:01_score :" . $redis->hsetnx('car:01', 'score' , 59);


echo "<br>hincrby: car:01_score :" . $redis->hincrby('car:01', 'score' , 5);	//+5

$car01 = $redis->hGetAll('car:01');
echo "<br>hGetAll: car01 :" ; print_r($car01);

//删除字段
echo "<br>hdel: " . $redis->hdel('car:01', 'key1');


$redis->hMset('user:1', array('name' => 'Joe', 'salary' => 2000));		

// 获取多个值
$user_1 = $redis->hMget('user:1' , array('name' , 'salary'));
echo "<br>hMget: user:1 :" ; print_r($user_1);



//=== 列表类型 List ====//





echo "<br><br>";



