<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\DetailWidget;
/** @var $this \Zodream\Domain\View\View */
/** @var $model \Domain\Model\FeedbackModel */
$this->title = $model->name;
$this->extend('layout/head');
?>


<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title"><?=$model->name?></h3>
      </div>
      <div class="panel-body">
            <?=DetailWidget::show([
                'data' => $model,
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


<?=$this->extend('layout/foot')?>