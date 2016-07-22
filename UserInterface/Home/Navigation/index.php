<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Authentication\Auth;
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
$data = $this->gain('data', array());
?>

<div class="main">
    <div class="search">
        <select id="s">
            <option value="baidu">百度</option>
            <option value="bing">必应</option>
            <option value="github">Github</option>
        </select>
        <input type="text" id="p" placeholder="搜索">
        <button id="search">搜索</button>
    </div>
    
    <div class="table">
        <div class="row">
            <div>
                站内
            </div>
            <div class="column">
                <div>
                    <a href="<?php $this->url('blog');?>">博客</a>
                </div>
                 <div>
                    <a href="<?php $this->url('laboratory');?>">实验室</a>
                </div>
                 <div>
                    <a href="<?php $this->url('talk');?>">日志</a>
                </div>
            </div>
        </div>
        <?php foreach ($data as $value) {?>
         <div class="row">
            <div>
                <?php echo $value[0]['category'];?>
            </div>
            <div class="column">
                <?php foreach ($value[1] as $item) {?>
                <div>
                    <a href="<?php echo $item['url'];?>" target="_blank"><?php echo $item['name'];?></a>
                </div>
                <?php }?>
            </div>
        </div>
        <?php }?>
        <?php if (!Auth::guest()) {?>
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
                        <?php foreach($this->gain('category', array()) as $item) {?>
                        <option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
                        <?php }?>
                    </select>
                    <button type="submit">增加</button>
                </form>
            </div>
        </div>
        <?php }?>
    </div>
</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        '!js require(["home/navigation"]);'
    )
);
?>