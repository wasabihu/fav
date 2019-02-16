<?php

//===== 定义常量 ======//
$max_connections = 20;
$url_list  = array('http://www.163.com/' , 'http://weibo.com/' , 'http://www.google.com/',
// 		'http://www.ruanyifeng.com/blog/2013/05/Knuth%E2%80%93Morris%E2%80%93Pratt_algorithm.html',
		'http://58.218.147.147:6121/android.php?c=category&m=categoryinfo&module=bizhi',
		);

$start_time = time();

$dead_urls = array();
$not_found_urls =array();
$working_urls = array();

// 1. 批处理器
$mh = curl_multi_init();

// 2. 加入需批量处理的URL
for ($i = 0; $i < 2; $i++) {
	add_url_to_multi_handle($mh, $url_list);
}

$active = null;
// 3. 初始处理
do {
	$mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);

// 4. 主循环
while ($active && $mrc == CURLM_OK) {
	// 5. 有活动连接
	if (curl_multi_select($mh) != -1) {
		// 6. 干活
		do {
			$mrc = curl_multi_exec($mh, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		
		
		// 7. 有信息否？
		if ($mhinfo = curl_multi_info_read($mh)) {
			// 意味着该连接正常结束
			// 8. 从curl句柄获取信息
			$chinfo = curl_getinfo($mhinfo['handle']);
			
			
			// 9. 死链么？
			if (!$chinfo['http_code']) {
				$dead_urls []= $chinfo['url'];
				// 10. 404了?
			} else if ($chinfo['http_code'] == 404) {
				$not_found_urls []= $chinfo['url'];
				// 11. 还能用
			} else {
				$working_urls []= $chinfo['url'];
				
				$data = curl_multi_getcontent($mhinfo['handle']);
				
				
				echo "url: {$chinfo['url']} <br><br>";
// 				print_r($data);
// 				echo "<br><br>";
			}
			
			// 12. 移除句柄
			curl_multi_remove_handle($mh, $mhinfo['handle']);
			curl_close($mhinfo['handle']);
			
			// 13. 加入新URL，干活
			if (add_url_to_multi_handle($mh, $url_list)) {
				do {
					$mrc = curl_multi_exec($mh, $active);
					
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
		}
	}
}


$end_time = time() - $start_time;

echo "<br>一共用了 $end_time 秒时间";

// 14. 完了
curl_multi_close($mh);
echo "==Dead URLs==\n<br>";
echo implode("\n",$dead_urls) . "\n\n<br><br>";
echo "==404 URLs==\n<br><br>";
echo implode("\n",$not_found_urls) . "\n\n<br><br>";
echo "==Working URLs==\n";
echo implode("\n",$working_urls);


// 15. 向批处理器添加url
function add_url_to_multi_handle($mh, $url_list) {
	static $index = 0;
	
	
	// 如果还剩url没用
	if (isset($url_list[$index]) && $url_list[$index]) {
		
// 		echo "index:$index<br>";
// 		echo "<br>".$url_list[$index];

		// 新建curl句柄
		$ch = curl_init();
		
		// 配置url
		curl_setopt($ch, CURLOPT_URL, $url_list[$index]);
		
		// 不想输出返回的内容
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		// 重定向到哪儿我们就去哪儿
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		
		
		// 不需要内容体，能够节约带宽和时间
// 		curl_setopt($ch, CURLOPT_NOBODY, 1);
		
		// 加入到批处理器中
		curl_multi_add_handle($mh, $ch);
		// 拨一下计数器，下次调用该函数就能添加下一个url了
		$index++;
		return true;
	} else {
		// 没有新的URL需要处理了
		return false;
	}
}