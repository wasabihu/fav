<?php

	$domain = $_GET['url'];
// content of somefile.php
  include('pagerank.php');
  
  $pr = getPageRank($domain);
  echo 'pagerankcode.com has PR '.$pr;
?>