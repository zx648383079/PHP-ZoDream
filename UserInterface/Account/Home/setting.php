<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
use Zodream\Domain\Access\Auth;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Domain\Html\Page */

$this->registerCssFile('zodream/blog.css');
$this->extend([
    'layout/head',
    'layout/navbar'
]);
?>

<div class="container">

    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <?=Html::a('个人信息', 'info')?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('安全中心', ['security'])?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('隐私设置', ['setting'])?>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <ul class="list-group">
                <li class="list-group-item">Cras justo odio</li>
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Morbi leo risus</li>
                <li class="list-group-item">Porta ac consectetur ac</li>
                <li class="list-group-item">Vestibulum at eros</li>
            </ul>
        </div>
    </div>
</div>

<?php $this->extend('layout/foot')?>
