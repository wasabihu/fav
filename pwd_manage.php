<?php
  require('includes/application_top.php');
  
  $contrl = new ContrlAction();
  $contrl->dipatchAction();
  
function indexAction(){
    global $smarty,$user_id,$contrl;
  
    
    $uid='';

   $list = get_list($user_id); 

//  $smarty->assign('list_count',$count);
  $smarty->assign('data_list',$list);
  
//  print_r ($list);
  
  
  
  $smarty->display('weibo_timing/add.html');
  
}

function get_slt($uid=0){
	
	$key =  uid + time();
	
	return  'pwds'.substr($key, 0,4);
}

/**
 * 插入
 */
function insertAction(){
    global $wpdb,$user_id ,$smarty;
	
    if(! $user_id){
        die('没有权限！');
    }
    
     $db_data = get_param_by_mysql(array('title' , 'url' ,'description' , 'pwd' ));
     
    
     $db_data['uid'] = $user_id;
     
     $db_data['is_encode'] = intval($_REQUEST['is_encode']);
     $db_data['display_pwd'] = intval($_REQUEST['display_pwd']);
     $db_data['seq'] = intval($_REQUEST['seq']);
     
     if ($db_data['is_encode']){
     	$db_data['slt'] = get_slt($user_id);
     	$db_data['pwd'] = wa_encrypt($db_data['pwd'] , $db_data['slt']);
     }

     $db_data['inserttime'] = date('Y-m-d H:i:s');
     $db_data['updatetime'] = $db_data['inserttime'];
     
//     print_r($db_data);
     
     $insert_id = $wpdb->insert('pwd_item', $db_data);
     
     if($insert_id)
        tep_redirect('pwd_manage.php');
    else {
        echo "插入不成功～！";
    }
     
}

function pwd_decode_pwd($encode_pwd , $slt){
	
	$str =  wa_encrypt($encode_pwd ,$slt ,'DECODE');
	
	return $str;
}


function get_list($user_id){
     global $wpdb;
     
     if(empty($user_id))         return FALSE;
     
    $sql = "select * from pwd_item where uid={$user_id} AND stat=1 order by seq";
    
    $list = $wpdb->get_results($sql);
    
    $data_list = array();
    foreach ($list as $row){
    	if ($row['display_pwd']){
    		
    		if ($row['is_encode']){
    			$row['pwd'] = pwd_decode_pwd($row['pwd'] , $row['slt']);
    			
    		}
    	}
    	else 
    		$row['pwd'] = '********';
    	
    	$row['title']	 = stripslashes($row['title']);	
    	
    	$row['description']	 = stripslashes($row['description']);
    	
    	if (mb_strlen($row['description']) >30)
    		$row['description'] = mb_substr($row['description'], 0,30). '...';
    	
    	$data_list[] = $row;
    	
    }
    
    
    return $data_list;
    
}

function get_param_by_mysql($param_name_list){
   
    $data = array();
    foreach ($param_name_list as $name){
        $data[$name] = mysql_real_escape_string($_REQUEST[$name]);
    }
    
    return $data;
}

?>
