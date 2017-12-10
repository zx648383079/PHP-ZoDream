<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Html\Page */
$this->extend('layout/header');
?>
<div class="row">
    <form method="POST">
        <h4 class="col-xs-2 text-center">
            随想：
        </h4>
        <div class="col-xs-8">
            <textarea rows="1" class="form-control" placeholder="内容" name="content" required></textarea>
        </div>
        <div class="col-xs-2">
            <button type="submit" class="btn btn-primary">发表</button>
        </div>
    </form>
</div>


    <div class="row">
        <h4 class="col-xs-1 text-center">
            ID
        </h4>
        <h4 class="col-xs-8 text-center">
            内容
        </h4>
        <h4 class="col-xs-3">
            操作
        </h4>
    </div>

        <?php foreach($page->getPage() as $value):?>
    <div class="row">
        <form method="POST">
            <h4 class="col-xs-1 text-center">
                <?=$value['id']?>
                <input type="hidden" name="id" value="<?=$value['id']?>">
            </h4>
            <div class="col-xs-8">
                <textarea rows="1" class="form-control" placeholder="内容" name="content" required><?=$value['content']?></textarea>
            </div>
            <div class="col-xs-3">
                <button type="submit" class="btn btn-primary">发表</button>
                <a href="#" class="btn btn-danger">删除</a>
            </div>
        </form>
    </div>
    <?php endforeach;?>

    <div class="row">
        <?php $page->pageLink();?>
    </div>

<?=$this->extend('layout/footer')?>