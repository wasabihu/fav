<?php

/**
 * ContrlAction.php
 *
 */

class ContrlAction{
    public $action = '';
    
    public $curr_page;
        
    public $pageTag; 
    
    public $actionType = 'class';
    
    public $defaultFunction_name; //默認function的名稱
    
    public $specialArray = array();	//要特別處理的function
    
//    public $procat_type;
//    public $cat_type = 'chinese';
//    public $wpdb;
    

    function __construct($pageTag='',$defaultFunction='') {
    	global $curr_page;
    	
  		
    	  if 		(isset($_REQUEST['act']) )		$this->action = $_REQUEST['act']; 
    	  elseif	(isset($_REQUEST['action']) )		$this->action = $_REQUEST['action'];	
    	
    	$this->curr_page = $curr_page;
    	$this->pageTag = $pageTag;
    	
    	
    	//初始化默認function
    	if(! empty($defaultFunction))	$this->defaultFunction_name = $defaultFunction;
		else    						$this->defaultFunction_name = 'index'.$this->pageTag. 'Action';

   }
   
   function dipatchAction($function_name=''){
   	
   		if(empty($function_name)){
   			
   			if(empty($this->action))		$function_name = $this->defaultFunction_name;
   			else							$function_name = $this->action.$this->pageTag. 'Action';
   		}

   		
   		$this->execut($function_name);
   }
   
   //執行方法
   function execut($function_name){
   	
   		if(function_exists($function_name))
   			call_user_func($function_name);
   		else
   			call_user_func($this->defaultFunction_name);

   }

}//END...
?>
