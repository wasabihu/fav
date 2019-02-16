<?php


/**
 * 封裝好的curl方法
 *
 * @param String $url  			要訪問的的url。
 * @param Array $parm_arr		參數数组。
 * @param Bool $is_show		  	是否直接显示。
 * @return Array $data			
 */
function wa_curl($url,$parm_arr=array(),$is_show=true){
	
	//第一步，我们通过函数curl_init()创建一个新的curl会话
	$curl = curl_init();

	// 设置你需要抓取的URL
	curl_setopt($curl, CURLOPT_URL, $url);
	
	
	if(!empty($parm_arr)){
		$curlPost = http_build_query($parm_arr);
		
		//设置post参数
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$curlPost);
	}
	
	
	// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。 ,如果這里設為TRUE，即不會直接輸出到屏幕上。
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	//第一个很有意思的参数是 CURLOPT_FOLLOWLOCATION ,当你把这个参数设置为true时，curl会根据任何重定向命令更深层次的获取转向路径
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	
	
	// 运行cURL，请求网页, 做完上一步工作，curl的准备工作做完了，curl将会获取URL站点的内容.
	$data = curl_exec($curl);
	
	// 关闭URL请求
	curl_close($curl);
	
	if($is_show)	print_r($data);
	
	return $data;
	
}
?>