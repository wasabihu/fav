<?php

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
	
	$item_id=6;


	$insert_sql = "insert into link(`item_id`,`name`,`href`,`description`,`seq`)".
		" VALUES($item_id,'$element->innertext','$element->href','',$i);";
	
//	 echo $element->href .'    '. $element->innertext .'<br>'; 

	echo $insert_sql.'<br>';

	$i++;
	if($i>=500)die;
       
}
?>