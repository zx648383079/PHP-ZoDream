<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Access\Auth;
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Domain\View\View */
$this->title = '导航';
$this->extend([
    'layout/header',
    'layout/navbar'
]);
$this->registerJs('require(["home/navigation"]);');
?>

<div class="main">
    <div class="search">
        <div class="row">
            <div class="col-md-2">
                <select id="s" class="form-control">
                    <option value="baidu">百度</option>
                    <option value="bing">必应</option>
                    <option value="github">Github</option>
                </select>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control" id="p" placeholder="搜索">
            </div>
            <div class="col-md-2">
                <button id="search" class="btn btn-primary">搜索</button>
            </div>
        </div>
    </div>
    
    <div class="table">
        <div class="row">
            <div>
                站内
            </div>
            <div class="column">
                <div>
                    <a href="<?=Url::to('blog');?>">博客</a>
                </div>
                 <div>
                    <a href="<?=Url::to('laboratory');?>">实验室</a>
                </div>
                 <div>
                    <a href="<?=Url::to('talk');?>">日志</a>
                </div>
            </div>
        </div>
        <?php foreach ($data as $value) :?>
         <div class="row">
            <div>
                <?=$value[0]['category'];?>
            </div>
            <div class="column">
                <?php foreach ($value[1] as $item) :?>
                <div>
                    <a href="<?=$item['url'];?>" target="_blank"><?=$item['name'];?></a>
                </div>
                <?php endforeach;?>
            </div>
        </div>
        <?php endforeach;?>
        <?php if (!Auth::guest()) :?>
        <div class="row">
            <div>
                增加
            </div>
            <div class="column">
                <div>
                    <a id="addCategory" href="javascript:0;">分类</a>
                </div>
                <div>
                    <a id="addWeb" href="javascript:0;">网址</a>
                </div>
            </div>
        </div>
        <div id="category" class="dialog">
            <div>
                <form method="POST">
                    <input type="text" name="name" placeholder="分类">
                    <button type="submit">增加</button>
                </form>
            </div>
        </div>
        <div id="web" class="dialog">
            <div>
                <form method="POST">
                    名称：<input type="text" name="name" value="" placeholder="名称"> </br>
                    网址：<input type="text" name="url" value="" placeholder="网址"> </br>
                    分类：<select name="category_id">
                        <?php foreach($category as $item) :?>
                        <option value="<?=$item['id'];?>"><?=$item['name'];?></option>
                        <?php endforeach;?>
                    </select>
                    <button type="submit">增加</button>
                </form>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>


<?php $this->extend('layout/footer')?>