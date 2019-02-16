<?php

$curr_page = basename($_SERVER['SCRIPT_NAME']);
require( 'includes/classes/ContrlAction.php');


$contrl = new ContrlAction();
$contrl->dipatchAction();


function totableAction(){
	
	$text = $_POST['text'];
	
	$text = nl2br($text);
	
	$str_arr = explode('<br />',$text);
	
	
	$str = '';	$count=0;
	foreach($str_arr as $line){
		$line = trim($line);
		if($count==0)	$str = '{|border="1" cellspacing="0" style="border-color: #CCCCCC;"';
		
		$str .= "<br />|-<br />|";
		$str .= str_replace(' 	', " || ", $line);
		
		$count++;
	}
	
	$str .= "<br />|}";

	echo $str .'<br /><br /><br />';
	
	indexAction();
	
}

function indexAction(){
	
	$text = $_POST['text'];
	
	echo <<<HTML
  		
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Insert title here</title>
</head>
<body>
<form action="?act=totable" method="post">
  <textarea name="text" cols="100" rows="6">$text</textarea>
  <br /><br />
<input type="submit" value="提 交" />
</form>
</body>
</html>
HTML;

}

?>