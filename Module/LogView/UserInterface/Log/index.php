<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Bootstrap\TableWidget;
/** @var $this View */
$this->title = 'ZoDream Log Viewer';
?>


<div class="over-table">
    <?=TableWidget::show([
        'page' => $log_list,
        'columns' => [
            'id' => 'Id',
            'date' => '日期',
            'time' => '时间',
            'cs_method' => '方法',
            'cs_uri_stem' => 'URL资源',
            'cs_uri_query' => 'URL查询',
            's_port' => '服务器端口',
            'c_ip' => '客户端IP',
            'cs_user_agent' => '用户代理',
            'cs_referer' => '引用网站',
            'cs_cookie' => 'Cookie',
            'time_taken' => '所用时间',
        ]
    ])?>
</div>
