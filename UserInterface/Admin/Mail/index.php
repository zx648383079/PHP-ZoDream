<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\FormWidget;
/** @var $this \Zodream\Domain\View\View */
$this->extend(array(
    'layout' => array(
        'head'
    )), array(
        'zodream/add.css'
    )
);
?>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">增加规格</h3>
        </div>
        <div class="panel-body">
            <?=FormWidget::begin()
                ->textArea('email', ['label' => '邮箱', 'required' => true])
                ->text('title', ['label' => '标题', 'required' => true])
                ->checkbox('html', ['label' => '是否为HTML', 'text' => '是'])
                ->textArea('content', ['label' => '内容', 'required' => true])
                ->button()
                ->end();
            ?>
            <p class="text-danger">
                成功：<?php $this->out('success', 0);?>个；失败：<?php $this->out('failure', 0);?>个；错误信息：<?php $this->out('message');?>
            </p>
        </div>
    </div>


<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>