<?php
defined('APP_DIR') or exit();
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
$page = $this->get('page');
?>
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="term list-group">
                <li class="list-group-item"><a href="<?php $this->url('blog');?>">全部</a></li>
                <?php foreach ($this->get('term', array()) as $item) {?>
                    <li class="list-group-item"><a href="<?php $this->url(null, array('termid' => $item['id']));?>"><?php echo $item['name'];?></a></li>
                <?php }?>
            </ul>
        </div>
        <div id="list" class="col-md-10">
            <ul class="list">
                <?php foreach ($page->getPage() as $item) {?>
                    <li class="list-item">
                        <div class="recommend" data="<?=$item['id'];?>">
                            <span><?=$item['recommend']?></span>
                            <span>推荐</span>
                        </div>
                        <h4 class="list-item-head">
                            <a href="<?php $this->url('blog/view/id/'.$item['id']);?>">
                                <?=$item['title'];?>
                            </a>
                        </h4>
                        <div class="list-item-content">
                            <?=$item['excerpt'];?>
                        </div>
                        <div class="list-item-foot">
                            <a href="<?php $this->url('blog/user/'.$item['user_id']);?>"><?=$item['user'];?></a>
                             发表于 
                            <?php $this->ago($item['create_at']);?>   
                            分类：<?=$item['term'];?>
                             评论（<?=$item['comment_count'];?>）
                        </div>
                    </li>
                <?php }?>
            </ul>
            <div>
                <?php $page->pageLink();?>
            </div>
        </div>
    </div>

</div>

    <div class="fixed-slide">
        <ul>
            <li><a href="#" target="_blank"><div class="box"><img src="<?php $this->asset('images/icon/icon01.png');?>">客服中心</div></a></li>
            <li><a href="#" target="_blank"><div class="box"><img src="<?php $this->asset('images/icon/icon02.png');?>">客户案例</div></a></li>
            <li><a href="#" target="_blank"><div class="box"><img src="<?php $this->asset('images/icon/icon04.png');?>">QQ客服</div></a></li>
            <li><a href="#" target="_blank"><div class="box"><img src="<?php $this->asset('images/icon/icon03.png');?>">新浪微博</div></a></li>
            <li><a href="javascript:;" class="toTop"><div class="box"><img src="<?php $this->asset('images/icon/icon05.png');?>">返回顶部</div></a></li>
        </ul>
    </div>
<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        '!js require(["home/blog"]);'
    )
);
?>