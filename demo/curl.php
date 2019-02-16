<?php
/*
// 初始化一个 cURL 对象
$curl = curl_init();

// 设置你需要抓取的URL
curl_setopt($curl, CURLOPT_URL, 'http://wasa.sinaapp.com/index.php');

// 设置header
curl_setopt($curl, CURLOPT_HEADER, 1);

// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// 运行cURL，请求网页
$data = curl_exec($curl);

// 关闭URL请求
curl_close($curl);

// 显示获得的数据
var_dump($data);

*/

/************************************************************************************************/

$url = 'http://wasa.sinaapp.com/index.php';

include('simple_html_dom.php');


//echo file_get_html('http://wasa.sinaapp.com/index.php')->plaintext;

$html = file_get_html($url);

// Find all images
/*
foreach($html->find('img') as $element)
       echo $element->src . '<br>';
*/       

$i=1;
// Find all links
foreach($html->find('a') as $element){
/*
http://www.phpfans.net/manu/javascript/    a   
 <a href="http://www.phpfans.net/manu/javascript/" target="_blank">javascript 手册</a>    
 javascript 手册    
 javascript 手册
 <br><br> <br>http://www.phpchina.com/    a    <a href="http://www.phpchina.com/" target="_blank">PHPChina</a>    PHPChina    PHPChina<br><br> <br>http://www.phpfans.net/    a    <a href="http://www.phpfans.net/" target="_blank">php爱好者</a>    php爱好者    php爱好者<br><br> <br>http://www.phpv.net/    a    <a href="http://www.phpv.net/" target="_blank">PHP5研究室</a>    PHP5研究室    PHP5研究室<br><br> <br>http://www.phpx.co
*/
	echo "<br> <br>";
	
	 echo $element->href .'    '. $element->tag 
	 .'    '. $element->outertext .'    '. $element->innertext .'    '. $element->plaintext
	 .'<br>'; 

	$i++;
	if($i>=230)die;
       
}



?>