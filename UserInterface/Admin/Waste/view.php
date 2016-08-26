<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\DetailWidget;
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/head');
?>


<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title"><?=$data['code']?></h3>
      </div>
      <div class="panel-body">
            <?=DetailWidget::show([
                'data' => $data,
                'items' => [
                    'id' => 'ID',
                    'code' => '编码',
                    'name' => '名称',
                    'content' => '介绍',
                    'damage' => '危害',
                    'treatment' => '处理方法',
                    'update_at'	=> '更新时间:datetime',
                    'create_at' => '创建时间:datetime'
                ]
            ])?>
      </div>
</div>


<?=$this->extend('layout/foot')?>