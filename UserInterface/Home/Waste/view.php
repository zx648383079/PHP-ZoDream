<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\DetailWidget;
use Zodream\Domain\Html\Bootstrap\BreadcrumbWidget;
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    ))
);
$data = $this->get('data');
?>

<div class="row">
    <?=BreadcrumbWidget::show([
        'links' => [
            '废料科普' => 'waste',
            $data['code']
        ]
    ]);?>
</div>

<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title">废料编号：<?=$data['code']?></h3>
      </div>
      <div class="panel-body">
            <?=DetailWidget::show([
                'data' => $data,
                'items' => [
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

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">相关企业：</h3>
        </div>
        <div class="panel-body">
            <?php foreach ($this->get('models', array()) as $item):?>
                <div class="col-md-2 waste-item">
                    <h4><?=Html::a($item['name'], ['waste/company', 'id' => $item['id']])?></h4>
                    <p>联系方式：<?=$item['phone']?></p>
                </div>
            <?php endforeach;?>
        </div>
    </div>

<?php 
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>