<?php
include('curl_funs.php');


$url = "http://121.9.213.59/autotags/api.php";

$data = array('title'=>'fdsafdsfsdfdsfdsfds', $data=>'坐飞机远赴美国、欧洲出差或旅行的朋友，免不了处理“倒时差”的问题。时差问题让人在当地应该休息的时间没能休息，在应当清醒的时候没能清醒。这个问题该如何解决？网络上流传着各式各样的经验，比如有人提议出发前晚睡晚起，到了当地便可自然地跟上当地的时间节奏，比如有人提议到了美国第一天一定不能睡等等');



$re_data = wa_curl($url,$data,true);

echo "<br><br>==========================================data==<br>";
print_r($re_data);

die;



?>