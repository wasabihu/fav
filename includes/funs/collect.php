<?php
/**
 * 采集function
 */

if (!class_exists('Snoopy')){
	include ROOT_PATH.'lib/snoopy.class.php';
}

include ROOT_PATH.'lib/simplehtmldom/simple_html_dom.php';

/**
 * 采集锵锵三人行
 * @return multitype:NULL
 */
function collect_qq3srx(){
	
	$url = "http://phtv.ifeng.com/program/qqsrx/";
	
	$list = array();
	
	$snoopy = new Snoopy;
// 	$snoopy->agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.0.5) Gecko/2008120122 Firefox/3.0.5 FirePHP/0.2.1";//这项是浏览器信
	$snoopy->fetch($url); //获取所有内容

	$html = str_get_html($snoopy->results);
	
	
	
	foreach($html->find('div.piclist li') as $e){
	
		$data = array();
		foreach($e->find('img') as $e2){
	
			$data['src'] = $e2->src ;
		}
	
	
		foreach($e->find('em') as $e2){
	
			$data['date'] = $e2->innertext ;
		}
	
		foreach($e->find('p a') as $e2){
	
			$data['link'] = $e2->href ;
			
			$data['name'] = $e2->innertext;
		}
	
		$list[] = $data;
	}
	
	return $list;	
}
