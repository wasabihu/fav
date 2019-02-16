<?php
require('includes/application_top.php');

include_once( 'weibov2/config.php' );
include_once( 'weibov2/saetv2.ex.class.php' );

//==== 全局变量要在这里申请 ================//
$contrl = new ContrlAction();


$contrl->dipatchAction();


function testAction()
{

    $data = _get_collect_data();

    print_r($data);
}


function _get_collect_data()
{

    $mmc = get_mmc();

    include DIR_WS_INCLUDES . 'funs/collect.php';

    $data = array();

    //锵锵三人行
    $qq3srx_data = memcache_get($mmc, 'collect_qq3srx');

    if (empty($qq3srx_data)) {

        $qq3srx_data = collect_qq3srx();
        memcache_set($mmc, 'collect_qq3srx', $qq3srx_data, 3600 * 12);
    } else {
//   		echo "cached!!!";
    }

    $data = $qq3srx_data;


    return $data;
}

function indexAction(){
    global $smarty, $user_id, $contrl, $mmc;
    $uid = '';

    $agent = $_SERVER['HTTP_USER_AGENT'];

    if ($_GET['debug'] == 995 || stripos($agent, "NetFront") || stripos($agent, "iPhone") || stripos($agent, "MIDP-2.0") || stripos($agent, "Opera Mini") || stripos($agent, "UCWEB") || stripos($agent, "Android") || stripos($agent, "Windows CE") || stripos($agent, "SymbianOS") || stripos($agent, "MQQBrowser")) {

        tep_redirect('m/index.php');
    }


    if ($user_id) {
        $uid = $user_id;
        $item_arr = getItemsBy_uid($uid);

// 	 	print_r($item_arr);


        $bool = ($_REQUEST['u'] == 'wasabi') ? false : true;

// 	 	$bool = true;

        $smarty->assign('is_edit', $bool);
    } else {
        $is_redirect = false;

        if ($contrl->action == 'inv') $is_redirect = true;

		initSina($is_redirect);
        $item_arr = getTempItems();
    }


    $cache_key = "item_list_{$uid}";

    $item_list = FALSE;


    $count = 0;

    foreach ($item_arr as $data) {
        $item_id = $data['id'];

        $data['link_list'] = getLinks($item_id);

        $item_list[] = $data;

        $count++;
    }


    $smarty->assign('item_length', $count);
    $smarty->assign('item_list', $item_list);

//   echo "<br><br>";print_r($item_list);


//  if($smarty->is_cached('index.html'))	echo 'cached....~<br>';
    $smarty->display('index.html');


}


function adminAction(){
    global $smarty, $user_id, $contrl;


    if (empty($user_id) || $user_id != ADMIN_UID) {
        die('你没有权限!');
    }


    $item_arr = getTempItems();
    $smarty->assign('is_edit', true);


    $count = 0;
    foreach ($item_arr as $data) {
        $item_id = $data['id'];

        $data['link_list'] = getLinks($item_id);
        $item_list[] = $data;

        $count++;
    }

    $smarty->assign('item_length', $count);
    $smarty->assign('item_list', $item_list);

//  if($smarty->is_cached('index.html'))	echo 'cached....~<br>';
    $smarty->display('index.html');


}

function initSina($is_redirect = false){
    global $smarty;


    $o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
    $aurl = $o->getAuthorizeURL(WB_CALLBACK_URL);


//     echo "loginUrl == $aurl <br>";
    $smarty->assign('loginUrl', $aurl);
}


//来至新浪的邀请
function sinaInviteAction()
{
    initSina(true);
}


function logoutAction()
{

    User::logout();

    tep_redirect('index.php');

}


function update_item_list_cache()
{
    global $user_id;

    $cache_key = "item_list_{$user_id}";

    $mmc = memcache_init();
    $mmc->delete($cache_key, 0);

}

function add_itemAction()
{
    global $wpdb, $user_id;

    if (empty($user_id)) echo 0;

    $data['uid'] = $user_id;

    $data['title'] = $wpdb->_escape($_POST['title']);
    $data['description'] = $wpdb->_escape($_POST['description']);
    $data['sort'] = $wpdb->_escape($_POST['sort']);

    if (empty($data['sort'])) $data['sort'] = 80;


    $wpdb->insert('item', $data);


    update_item_list_cache();    //更新cache

    echo $wpdb->insert_id;


}

function delete_linkAction()
{
    global $wpdb;

    $linke_id = $wpdb->_escape($_POST['link_id']);

    if (empty($linke_id)) echo 0;

    $sql = "delete from link where id=$linke_id";

    $wpdb->query($sql);

    update_item_list_cache();    //更新cache

    echo 1;
}

// 修改连接
function edit_linkAction()
{
    global $wpdb;

    $data = array();

    $where['id'] = $wpdb->_escape($_POST['link_id']);


    $data['name'] = $wpdb->_escape($_POST['name']);
    $data['href'] = $wpdb->_escape($_POST['href']);
    $data['description'] = $wpdb->_escape($_POST['description']);
    $data['seq'] = $wpdb->_escape($_POST['seq']);

    if (empty($data['seq']) || !is_numeric($data['seq'])) $data['seq'] = 188;

    //新增
    if (empty($where['id'])) {
        $data['item_id'] = $wpdb->_escape($_POST['item_id']);
        $wpdb->insert('link', $data);

        echo "更新成功！";

    } else {    //修改

        $data['item_id'] = $wpdb->_escape($_POST['item_id']);
        $wpdb->update('link', $data, $where);
    }

    update_item_list_cache();    //更新cache

//	echo $where['id'];
}

// 修改分类 Item
function edit_itemAction()
{
    global $wpdb;

    if (!$_SESSION['user_id']) die('没有权限！');


    $where['id'] = $wpdb->_escape($_POST['item_id']);
    $where['uid'] = $wpdb->_escape($_SESSION['user_id']);

    $data = array();

    print_r($_POST);


    $data['title'] = $wpdb->_escape($_POST['title']);
    $data['sort'] = $wpdb->_escape($_POST['sort']);

    $data['page'] = (is_numeric($_POST['page'])) ? $_POST['page'] : 1;

    if (empty($data['sort']) || !is_numeric($data['sort'])) $data['sort'] = 188;

    //新增
    if (empty($where['id'])) {
        $data['item_id'] = $wpdb->_escape($_POST['item_id']);

        $wpdb->insert('item', $data);

        echo "更新成功！";
    } else {    //修改


        $wpdb->update('item', $data, $where);
    }

    update_item_list_cache();    //更新cache

//	echo $where['id'];
}


function update_weiboAction()
{
    global $smarty, $user_id;

    $user = $_SESSION['curr_user'];

    if (empty($user['oauth_token'])) $user = new User($user_id);


    //empty oauth_token,oauth_token_secret


    $c = new WeiboClient(WB_AKEY, WB_SKEY, $user['oauth_token'], $user['oauth_token_secret']);
    $ms = $c->home_timeline(); // done
    $me = $c->verify_credentials();

    $rr = $c->update($_REQUEST['text']);


    echo "发送完成";
}

function commentAction()
{
    global $smarty, $user_id;

    if (empty($_POST['text'])) {
        echo "內容為空，提交不成功！";
        return false;
    }

    $user = $_SESSION['curr_user'];


    if (empty($user['oauth_token'])) $user = new User($user_id);


    $c = new WeiboClient(WB_AKEY, WB_SKEY, $user['oauth_token'], $user['oauth_token_secret']);
    $ms = $c->home_timeline(); // done
    $me = $c->verify_credentials();

//	$id = '3344259161980413';
    $mid = '3344259161980413';

    //對mid 所在微博發表一條評論。
    $msg = $c->send_comment($mid, $_REQUEST['text'], null);


    echo "谢谢你提出宝贵意见！";
}


function weibo_testAction()
{
    global $smarty, $user_id;


    echo "start:";

    require(DIR_WS_CLASSES . 'weibo_collect.php');

    $weibo_uid = '1642635773';
    $weibo_collect = new Weibo_collect();

    $ms = $weibo_collect->user_timeline_by_id($weibo_uid);

    foreach ($ms['statuses'] as $weibo) {
        $created_at = strtotime($weibo['created_at']);

        echo "<br> <br>";

        echo "len: " . strlen($weibo['text']);

        echo "<br>len2: " . mb_strlen($weibo['text']);

        echo "<br>{$weibo['text']}";

        $weibo['create_data'] = date('Y-m-d H:i:s', $created_at);
// 		print_r($weibo);
    }

// 	echo "ms : <br>";
// 	print_r($ms);


}

?>

