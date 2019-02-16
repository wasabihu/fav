<?php

echo preg_replace_callback('/\b([a-z])([a-z]+)\b/i' , function ($matches){
	return strtoupper($matches[1]) . strtolower($matches[2]);
}
 , "one TWO tHREE");
 
 $a_str = preg_split ( '/\s+/' , "one\t two \n     three");
 
 echo "<br> <br>";
 print_r($a_str);