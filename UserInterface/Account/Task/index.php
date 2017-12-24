<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
use Zodream\Html\Bootstrap\AccordionWidget;
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Template\View */
/** @var $page \Zodream\Html\Page */
$this->extend([
    'layout/header',
    'layout/navbar'
]);
?>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <span class="badge">14</span>
                    <?=Html::a('任务大厅', ['task/all'])?>
                </li>
                <li class="list-group-item active">
                    <span class="badge">1</span>
                    <?=Html::a('我的任务', ['task/index'])?>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="list-group">
                <?php foreach ($data as $item) :?>
                    <a href="<?Url::to(['message/send', 'id' => $item['send_id']])?>" class="list-group-item active">
                        <span class="badge"><?=$this->ago($item['create_at']);?></span>
                        <h4 class="list-group-item-heading"><?=$item['name'];?></h4>
                        <p class="list-group-item-text"><?=$item['content']?></p>
                    </a>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>

<?php $this->extend('layout/footer')?>
