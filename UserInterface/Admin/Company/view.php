<?php
defined('APP_DIR') or exit();
use Zodream\Html\Bootstrap\DetailWidget;
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/header');
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
                    'name' => '公司名',
                    'description' => '介绍',
                    'charge' => '负责人',
                    'phone' => '联系方式',
                    'update_at'	=> '更新时间:datetime',
                    'create_at' => '创建时间:datetime'
                ]
            ])?>
      </div>
</div>


<?=$this->extend('layout/footer')?>