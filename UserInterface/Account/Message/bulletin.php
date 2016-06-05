<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
use Zodream\Domain\Html\Bootstrap\AccordionWidget;
/** @var $this \Zodream\Domain\Response\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    )), array(
        'zodream/blog.css'
    )
);
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
            <?=AccordionWidget::show([
                'data' => $this->get('data'),
                'item' => [
                    'title',
                    'content'
                ]
            ])?>
        </div>
    </div>
</div>

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>
