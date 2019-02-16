<?php

$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : 'http://wasa.sinaapp.com/';

$shorturl = shorturl($url);
echo "url == $url <br>  shorturl == ";
print_r($shorturl);


//echo "<br> unshorten==";
//echo unshorten($shorturl[0]);

//$url = "http://163.fm/1QLJ8U";
//echo unshorten($url);


/************************************************************/
function unshorten($url) 
{
	$url = trim($url);
	$headers = get_headers($url);
  	$location = $url;
  	$short = false;
  	foreach($headers as $head) 
	{
    	if($head=="HTTP/1.1 302 Found") 
			$short = true;
    	if($short && startwith($head,"Location: ")) 
		{
      		$location = substr($head,10);
    	}
  	}
  	return $location;
}

function startwith($Haystack, $Needle)
{
	return strpos($Haystack, $Needle) === 0;
}

/*****************************************************************************/

function shorturl($input) { 
  $base32 = array ( 
    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 
    'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 
    'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 
    'y', 'z', '0', '1', '2', '3', '4', '5' 
    ); 
  
  $hex = md5($input); 
  $hexLen = strlen($hex); 
  $subHexLen = $hexLen / 8; 
  $output = array(); 
  
  for ($i = 0; $i < $subHexLen; $i++) { 
    $subHex = substr ($hex, $i * 8, 8); 
    $int = 0x3FFFFFFF & (1 * ('0x'.$subHex)); 
    $out = ''; 
  
    for ($j = 0; $j < 6; $j++) { 
      $val = 0x0000001F & $int; 
      $out .= $base32[$val]; 
      $int = $int >> 5; 
    } 
  
    $output[] = $out; 
  } 
  
  return $output; 
} 
