<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\DetailWidget;
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
$data = $this->get('data');
?>


<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title"><?=$data['name']?></h3>
      </div>
      <div class="panel-body">
            <?=DetailWidget::show([
                'data' => $data,
                'items' => [
                    'id' => 'ID',
                    'name' => '称呼',
                    'email' => '邮箱',
                    'phone' => '联系方式',
                    'ip' => 'IP',
                    'user_id' => '用户ID',
                    'content' => '内容',
                    'create_at' => '创建时间:datetime'
                ]
            ])?>
      </div>
</div>


<?php 
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>