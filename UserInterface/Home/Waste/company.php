<?php
defined('APP_DIR') or exit();
use Zodream\Html\Bootstrap\DetailWidget;
use Zodream\Html\Bootstrap\BreadcrumbWidget;
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Domain\View\View */
$this->title = $title;
$this->extend([
    'layout/header',
    'layout/navbar'
]);
?>

<div class="row">
    <?=BreadcrumbWidget::show([
        'links' => [
            '废料科普' => 'waste',
            $data['name']
        ]
    ]);?>
</div>

<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title"><?=$data['name']?></h3>
      </div>
      <div class="panel-body">
            <?=DetailWidget::show([
                'data' => $data,
                'items' => [
                    'name' => '公司名',
                    'description' => '介绍',
                    'charge' => '负责人',
                    'phone' => '联系方式'
                ]
            ])?>
      </div>
</div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">主要处理的废料：</h3>
        </div>
        <div class="panel-body">
            <?php foreach ($models as $item):?>
            <div class="col-md-2 waste-item">
                <h4><?=Html::a($item['code'], ['waste/view', 'id' => $item['id']])?></h4>
                <p><?=$item['name']?></p>
            </div>
            <?php endforeach;?>
        </div>
    </div>


<?php $this->extend('layout/footer')?>