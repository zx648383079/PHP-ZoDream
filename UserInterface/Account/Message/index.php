<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
use Zodream\Html\Bootstrap\AccordionWidget;
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Html\Page */

$this->registerCssFile('zodream/blog.css');
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
                    <?=Html::a('私信', 'message/index')?>
                </li>
                <li class="list-group-item">
                    <span class="badge">1</span>
                    <?=Html::a('系统消息', ['message/bulletin'])?>
                </li>
                <li class="list-group-item">
                    <span class="badge">1</span>
                    <?=Html::a('通知', ['message/bulletin', 'type' => '1'])?>
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
