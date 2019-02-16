<?php

//header('Content-Type: text/html; charset=utf-8');

include 'phpQuery-onefile.php';  




if (isset($_REQUEST['act'])){
	
	$func = $_REQUEST['act'].'Action';

	$func();
}

else{
	indexAction();	
}



function pqAction(){
	
	
	phpQuery::newDocumentFile('http://2012.qq.com/medals/');
//	phpQuery::$debug = true;
	
	$companies = pq('table')->find('td.table_c_td_a');
  
	
	$count =0;
	
	$list = array();
foreach($companies as $company)  {
	
	
	
	$html = pq($company)->html();
	
	if (strstr($html, 'img') ){
		$count++;
			
		$pattern = '#<img[\s>][\s\S]*?src="http://mat1.qq.com/2008/frags/(.*?).gif.*?">#isU';
		preg_match_all($pattern, $html, $matches, PREG_OFFSET_CAPTURE);
		
//		print_r($matches[0]);
//		print_r($matches[1][0][0]);
		
		
//		echo pq($company)->text()."<br>";
		
//		echo pq($company)->html()."<br>";
		
		$data = array();
		$data['key'] = $matches[1][0][0];
		$data['val'] =  trim(pq($company)->text());
		$data['img'] = $matches[1][0][0].'.gif';
		
		
		
		$list[] = $data;
		
//		echo pq($company)->find('img')->html()."<br>";

		
	}
	
	
   	
   	
//   	echo pq($company)->attr("width") .'<br>';
}

$medals_list = Array(
    0 => Array
        (	'key' => 'CHN',
            'val' => '中国',
            'img' => 'CHN.gif'
        ),

    1 => Array
        (
            'key' => 'USA',
            'val' => '美国',
            'img' => 'USA.gif'
        )
	);
	
echo'array(';
	
foreach ($list as $key=>$data){
	echo "$key => array(";
	
	$count = 0;
	foreach ($data as $key2 =>$item){
		$count++;
		
		echo "'{$key2}' => '{$item}'";
		
		if ($count ==3){
			echo '),';
		}
		else echo ',';
		
	}
	
}
echo ");<br>";
//print_r($list);
// print_rr($list);

echo "count == $count <br>";

}




function pq3Action(){
	phpQuery::newDocumentFile('http://wasa.sinaapp.com/');
//	phpQuery::$debug = true;
	
	$companies = pq('#content table')->find('th');
  
	$count =0;
foreach($companies as $company)  {
	$count++;
   	echo pq($company)->attr("colspan")."<br>";
}

echo "count == $count <br>";

}


function pq2Action(){

	phpQuery::newDocumentFile('http://job.blueidea.com');  
$companies = pq('#hot-search')->find('a');
  
foreach($companies as $company)  {
//	echo $company .'<br>';
   echo pq($company)->text()."<br>";  
}  
	

}




function procAction(){
	
	$q =  $_REQUEST['q'];
	
	$text =  $_REQUEST['text'];
	
	
	echo "$q <br>";
	
	
	$pattern = $q.'isU';
	
	preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);
	
	
	print_r($matches);
	
	
}

function print_rr($arr){
	
	$str= 'array(';
	foreach($arr as $key=>$value){
		if(! is_numeric($key)){
			$str .= "[$key] => $value <br>";
		}
	}
	
	echo $str.");<br>";	
}


function indexAction(){
	
	
?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div class="setup_main">
	<h2>解释XML</h2>
	
			<form action="?act=proc" method="post"/>
			
                <div id="information_box" class="info_tab01" style="display: block;">
                <table>
                    <tbody>

         		 <tr>
                    <th class="gray6"><em class="error_color">*</em> 搜索：</th>
                    <td><input type="text" name="q" value="" ></td>
                  </tr>
				  
				  <tr>
                    <th class="gray6"><em class="error_color">*</em> text：</th>
                    <td>
					
					<textarea rows="6" id="text" name="text"></textarea>
					</td>
                  </tr>
				  
				  
				  
                  <tr>
                    <th></th>
                    <td><input type="submit"> </td>
                    <td></td>
                  </tr>
        
                  </form>
                  
                </tbody></table>

<?php 	
}
?>