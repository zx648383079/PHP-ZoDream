<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Bootstrap\TableWidget;
use Module\LogView\Domain\Tag;
/** @var $this View */
$this->title = 'ZoDream Log Viewer';
$columns = [
    's_sitename' => '服务名称',
    's_computername' => '服务器名称',
    's_ip' => '服务器IP',
    'cs_method' => '方法',
    'cs_uri_stem' => 'URL资源',
    'cs_uri_query' => 'URL查询',
    's_port' => '服务器端口',
    'cs_username' => '用户名',
    'c_ip' => '客户端IP',
    'cs_user_agent' => '用户代理',
    'cs_version' => '协议版本',
    'cs_referer' => '引用网站',
    'cs_cookie' => 'Cookie',
    'cs_host' => '主机',
    'sc_status' => '协议状态',
    'sc_substatus' => '协议子状态',
    'sc_win32_status' => 'win32状态',
    'sc_bytes' => '发送的字节数',
    'cs_bytes' => '接收的字节数',
    'time_taken' => '所用时间',
];
?>

<div class="page-search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <select name="name">
            <?php foreach($columns as $key => $item):?>
               <option value="<?=$key?>"><?=$item?></option>
            <?php endforeach;?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
</div>

<div class="over-table">
    <table class="table table-hover tag-table">
        <thead>
            <tr>
                <th><?=$columns[$name]?></th>
                <th>次数</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($top_list as $log):?>
               <tr class="<?=Tag::has($log) ? 'danger' : ''?>">
                    <td>
                        <span data-url="<?=$this->url('./log/tag', ['name' => $name])?>"><?=$log[$name]?></span>
                        <i class="fa fa-tag"></i>
                    </td>
                    <td>
                        <?=$log['count']?>
                    </td>
               </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<?=$top_list->getLink()?>
