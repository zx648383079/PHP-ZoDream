<?php
defined('APP_DIR') or exit();
use Zodream\Html\Bootstrap\FormWidget;
/** @var $this \Zodream\Domain\View\View */
$this->registerCssFile('zodream/add.css');
$this->extend('layout/header');
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
        </div>
    </div>


<?=$this->extend('layout/footer')?>