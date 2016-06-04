<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
use Zodream\Domain\Authentication\Auth;
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
            <img src="">
        </div>
        <div class="col-md-9">
            <p><?=Auth::user()['name']?></p>
            <p><?=Html::a('查看个人信息', 'user/info')?></p>
            <p>个性签名</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <?=Html::a('动态', 'message/index')?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('博客', ['message/index'])?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('论坛', ['message/index'])?>
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

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    )), array(
        function() {?>
            <script type="text/javascript">
                require(['home/blog']);
            </script>
        <?php }
    )
);
?>
