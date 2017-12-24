<?php
defined('APP_DIR') or exit();
use Zodream\Html\Bootstrap\DetailWidget;
use Zodream\Html\Bootstrap\BreadcrumbWidget;
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Template\View */
$this->title = $title;
$this->extend([
    'layout/header',
    'layout/navbar'
]);
?>

<div class="row">
    <?=BreadcrumbWidget::show([
        'links' => [
            '公司供求' => 'company',
            $data['name']
        ]
    ]);?>
</div>

<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title">公司名：<?=$data['name']?></h3>
      </div>
      <div class="panel-body">
            <?=DetailWidget::show([
                'data' => $data,
                'items' => [
                    'name' => '名称',
                    'description' => '介绍',
                    'address' => '地址',
                    'charge' => '负责人',
                    'phone' => '联系方式',
                    'product' => '产品',
                    'demand' => '需求'
                ]
            ])?>
      </div>
</div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">公司评价：</h3>
        </div>
        <div class="panel-body">
            <?php foreach ($this->gain('models', array()) as $item):?>
                <div class="col-md-2 waste-item">
                    <h4><?=$item['title']?></h4>
                    <?=$item['name']?> <?php $this->ago($item['create_at'])?> <span><?=$item['star']?></span>
                    <p><?=$item['content']?></p>
                </div>
            <?php endforeach;?>
        </div>
    </div>

<?php $this->extend('layout/footer')?>