<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Template\View */
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
            <div class="row">
                <form method="POST" class="form-horizontal" role="form">
                    <div class="form-group">
                        <legend>私信</legend>
                    </div>
                    <input type="hidden" name="user_id" value="<?php $this->out('user.id');?>">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea name="content" id="textarea_content" class="form-control" rows="3" required="required"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-9">
                            <button type="submit" class="btn btn-primary">回复</button>
                        </div>
                    </div>
                </form>

            </div>
            
            <div class="row">
                <div class="list-group">
                    <?php foreach ($data as $item) :?>
                        <a href="<?=Url::to(['message/send', 'id' => $item['send_id']])?>" class="list-group-item active">
                            <span class="badge"><?=$this->time($item['create_at']);?></span>
                            <p class="list-group-item-text"><?=$item['content']?></p>
                        </a>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->extend('layout/footer')?>
