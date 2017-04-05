<?php
use Zodream\Infrastructure\Disk\File;
use Zodream\Domain\Spider\Spider;
use Zodream\Infrastructure\Support\Curl;
define('DEBUG', true);
define('APP_DIR', dirname(dirname(__FILE__)));
require_once(APP_DIR.'/vendor/autoload.php');
/*$file = new File('a.txt');
Spider::loadUrl('')->map(function ($html) {
    return (new simple_html_dom($html))->find('', 1)->find('a');
})->switch(function ($url) use ($file) {
    $file->append(Spider::loadUrl($url)->map(function ($html) {
        $dom =  new simple_html_dom($html);
        $title = $dom->find('', 1)->text();
        $content = $dom->find('', 1)->text();
        return "$title\r\n\r\n$content\r\n\r\n\r\n";
    })->getData());
});*/
function saveUser($item) {
    $id = (new \Zodream\Infrastructure\Database\Query\Query())
        ->select('user_id')->from('admin_user')
        ->where(['user_name' => $item['rule_name']])->scalar();
    if (!empty($id)) {
        return;
    }
    $id = (new \Zodream\Infrastructure\Database\Query\Record())
        ->setTable('admin_user')
        ->set([
            'user_name' => $item['rule_name'],
            'email' => '1@qq.com',
            'password' => 'dc483e80a7a0bd9ef71d8cf973673924',
            'add_time' => time(),
            'action_list' => 'order_view',
            'nav_list' => '订单列表|order.php?act=list',
            'role_id' => 5
        ])->insert();
    $qr_id = (new \Zodream\Infrastructure\Database\Query\Record())
        ->setTable('wx_qrcode')
        ->set([
            'scene_id' => $item['qrcode']['scene_id'],
            'ticket' => $item['qrcode']['ticket'],
            'scan_count' => $item['qrcode']['scan_count']
        ])->insert();
    (new \Zodream\Infrastructure\Database\Query\Record())
        ->setTable('admin_qrcode')
        ->set([
            'user_id' => $id,
            'qr_id' => $qr_id,
        ])->insert();
}

function getQR() {
    $page = 200;
    $num = 1490790017525;

    while ($page > 0) {
        $num ++;
        try {
            $data = (new Curl('https://www.youzan.com/v2/weixin/autoreply/rule.json?page='.
                $page.'&rule_type=7&packaged=1&mp_id=775558&_='.$num))
                ->setReferrer('https://www.youzan.com/v2/weixin/autoreply/scan')
                ->setCookie('gr_user_id=18995ddb-0101-4e35-8676-2d05eff05d71; KDTSESSIONID=c48ef006902be59042a610e02c; UM_distinctid=15ad52c21ca61d-066295d37df7a6-67f1a39-100200-15ad52c21cbc2c; _canwebp=1; _kdt_id_=18129825; Hm_lvt_7bec91b798a11b6f175b22039b5e7bdd=1489634788,1490146980,1490254535,1490789974; Hm_lpvt_7bec91b798a11b6f175b22039b5e7bdd=1490789974; captcha_response=01f330be9b9e674aac5bf2ec099766999068ecff; avatar=https%3A%2F%2Fdn-kdt-img.qbox.me%2Fupload_files%2Favatar.png; mobile=13917861882; sid=a416f03650e42c168dfb91ff2f839138; sKey=GzumGWLrlNlu9Z4gm811cATbffTQ8H7f8TbIqdKrsnY%3D; user_id=782990; user_weixin=%2B86-13917861882; user_nickname=ykoko; kdtnote_fans_id=0; kdt_id=18129825; team_auth_key=af3b450e570c1d102fa690d8a7045f35; weixin_server_key=9621b2a606ec67df91f5a76afc7e0f68; weixin_oldsub_key=4c7b93460b460f22f5ffe9f1ab8dc15d; weixin_certsub_key=6fddb1954989ad38eed854cf67d038ba; weixin_menu_key=45fdf77f27a2213a252c1707146312a5; mp_id=775558; weixin_subscribe_key=ac786ecd65ccf6cab61eca1273a7deab; sinaweibo_id=18129825; sinaweibo_uid=5837662734; weibo_auth_key=c7058b0de766df818b65bc11da3e4cc3; _canwebp=1; Hm_lvt_7fff7ceede91c07fb0a2f9c1850d7987=1489634913,1490147031,1490254540,1490789997; Hm_lpvt_7fff7ceede91c07fb0a2f9c1850d7987=1490790018; gr_session_id_767813e963734402a8256e1096b88331=48bddf80-d623-472c-9a7c-7eabbd33dac7; gr_cs1_48bddf80-d623-472c-9a7c-7eabbd33dac7=kdt_id%3A18129825')
                ->setHeader([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36',
                    'Accept' => 'application/json, text/javascript, **; q=0.01'
                ])->get();
            $data = json_decode($data, true);
            if ($data['code'] !== 0) {
                throw new Exception('eee');
            }
            foreach ($data['data']['list'] as $item) {
                saveUser($item);
            }
            echo $page,' GOOD <br/>';
        } catch (Exception $ex) {
            echo $page,'<br/>';
            break;
        }
        $page --;
    }
}

function getOrder() {
    $page = 9;

    while ($page > 0) {
        try {
            $data = (new Curl('https://www.youzan.com/v2/trade/order/list.json?p='.
                $page.'&type=all&state=all&orderby=book_time&order_es_tag=&tuanId=&order=desc&page_size=20&disable_express_type='
                ))
                ->setReferrer('https://www.youzan.com/v2/weixin/autoreply/scan')
                ->setCookie('gr_user_id=18995ddb-0101-4e35-8676-2d05eff05d71; KDTSESSIONID=c48ef006902be59042a610e02c; UM_distinctid=15ad52c21ca61d-066295d37df7a6-67f1a39-100200-15ad52c21cbc2c; _canwebp=1; _kdt_id_=18129825; Hm_lvt_7bec91b798a11b6f175b22039b5e7bdd=1489634788,1490146980,1490254535,1490789974; Hm_lpvt_7bec91b798a11b6f175b22039b5e7bdd=1490789974; captcha_response=01f330be9b9e674aac5bf2ec099766999068ecff; avatar=https%3A%2F%2Fdn-kdt-img.qbox.me%2Fupload_files%2Favatar.png; mobile=13917861882; sid=a416f03650e42c168dfb91ff2f839138; sKey=GzumGWLrlNlu9Z4gm811cATbffTQ8H7f8TbIqdKrsnY%3D; user_id=782990; user_weixin=%2B86-13917861882; user_nickname=ykoko; kdtnote_fans_id=0; kdt_id=18129825; team_auth_key=af3b450e570c1d102fa690d8a7045f35; weixin_server_key=9621b2a606ec67df91f5a76afc7e0f68; weixin_oldsub_key=4c7b93460b460f22f5ffe9f1ab8dc15d; weixin_certsub_key=6fddb1954989ad38eed854cf67d038ba; weixin_menu_key=45fdf77f27a2213a252c1707146312a5; mp_id=775558; weixin_subscribe_key=ac786ecd65ccf6cab61eca1273a7deab; sinaweibo_id=18129825; sinaweibo_uid=5837662734; weibo_auth_key=c7058b0de766df818b65bc11da3e4cc3; _canwebp=1; Hm_lvt_7fff7ceede91c07fb0a2f9c1850d7987=1489634913,1490147031,1490254540,1490789997; Hm_lpvt_7fff7ceede91c07fb0a2f9c1850d7987=1490790018; gr_session_id_767813e963734402a8256e1096b88331=48bddf80-d623-472c-9a7c-7eabbd33dac7; gr_cs1_48bddf80-d623-472c-9a7c-7eabbd33dac7=kdt_id%3A18129825')
                ->setHeader([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36',
                    'Accept' => 'application/json, text/javascript, **; q=0.01'
                ])->get();
            (new File(__DIR__.'/json/o_'.$page.'.json'))
                ->write($data);
            echo $page,' GOOD <br/>';
        } catch (Exception $ex) {
            echo $page,'<br/>';
            break;
        }
        $page --;
    }
}

function getUser() {
    $page = 20;

    while ($page > 0) {
        try {
            $data = (new Curl('https://www.youzan.com/scrm/customer/customer/getlist.json?csrf_token=QlsqbyUpKTkJG3k6XUo6KwwNeS08MWERGgA4KEAkMHIWBSp6DCFVN01FJidcMz45DBMtICcNNCQRGCJuOBM%2FKFQNMT9NMTwNGw88KR90ICtRFSg4MSYgJVokXCkdJCFlUSEnNlY9dDFebSg4VA&p='.
                $page
            ))
                ->setReferrer('https://www.youzan.com/v2/weixin/autoreply/scan')
                ->setCookie('gr_user_id=18995ddb-0101-4e35-8676-2d05eff05d71; KDTSESSIONID=c48ef006902be59042a610e02c; UM_distinctid=15ad52c21ca61d-066295d37df7a6-67f1a39-100200-15ad52c21cbc2c; _canwebp=1; _kdt_id_=18129825; Hm_lvt_7bec91b798a11b6f175b22039b5e7bdd=1489634788,1490146980,1490254535,1490789974; Hm_lpvt_7bec91b798a11b6f175b22039b5e7bdd=1490789974; captcha_response=01f330be9b9e674aac5bf2ec099766999068ecff; avatar=https%3A%2F%2Fdn-kdt-img.qbox.me%2Fupload_files%2Favatar.png; mobile=13917861882; sid=a416f03650e42c168dfb91ff2f839138; sKey=GzumGWLrlNlu9Z4gm811cATbffTQ8H7f8TbIqdKrsnY%3D; user_id=782990; user_weixin=%2B86-13917861882; user_nickname=ykoko; kdtnote_fans_id=0; kdt_id=18129825; team_auth_key=af3b450e570c1d102fa690d8a7045f35; weixin_server_key=9621b2a606ec67df91f5a76afc7e0f68; weixin_oldsub_key=4c7b93460b460f22f5ffe9f1ab8dc15d; weixin_certsub_key=6fddb1954989ad38eed854cf67d038ba; weixin_menu_key=45fdf77f27a2213a252c1707146312a5; mp_id=775558; weixin_subscribe_key=ac786ecd65ccf6cab61eca1273a7deab; sinaweibo_id=18129825; sinaweibo_uid=5837662734; weibo_auth_key=c7058b0de766df818b65bc11da3e4cc3; _canwebp=1; Hm_lvt_7fff7ceede91c07fb0a2f9c1850d7987=1489634913,1490147031,1490254540,1490789997; Hm_lpvt_7fff7ceede91c07fb0a2f9c1850d7987=1490790018; gr_session_id_767813e963734402a8256e1096b88331=48bddf80-d623-472c-9a7c-7eabbd33dac7; gr_cs1_48bddf80-d623-472c-9a7c-7eabbd33dac7=kdt_id%3A18129825')
                ->setHeader([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36',
                    'Accept' => 'application/json, text/javascript, **; q=0.01'
                ])->get();
            (new File(__DIR__.'/json/u_'.$page.'.json'))
                ->write($data);
            echo $page,' GOOD <br/>';
        } catch (Exception $ex) {
            echo $page,'<br/>';
            break;
        }
        $page --;
    }
}

\Zodream\Service\Config::setValue('db', [
    'database' => 'zhihuxi',					//数据库
    'prefix'   => 'ecs_',					//前缀
]);
set_time_limit(0);
getUser();