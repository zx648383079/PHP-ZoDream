<?php
defined('APP_DIR') or exit();
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
            <ul class="term">
                <li><a href="<?php $this->url('blog');?>">全部</a></li>
                <?php foreach ($this->get('term', array()) as $item) {?>
                    <li><a href="<?php $this->url(null, array('termid' => $item['id']));?>"><?php echo $item['name'];?></a></li>
                <?php }?>
            </ul>
        </div>
        <div id="list" class="col-md-10">
            <ul class="list">
                <?php foreach ($page->getPage() as $item) {?>
                    <li class="list-item">
                        <h4 class="list-item-head">
                            <a href="<?php $this->url('blog/view/id/'.$item['id']);?>">
                                <?php echo $item['title'];?>
                            </a>
                        </h4>
                        <div class="list-item-content">
                            <?php echo $item['excerpt'];?>
                        </div>
                        <div class="list-item-foot">
                            <a href="<?php $this->url('blog/user/'.$item['user_id']);?>"><?php echo $item['user'];?></a>
                             发表于 
                            <?php $this->ago($item['create_at']);?>   
                            分类：<?php echo $item['term'];?>
                             评论（<?php echo $item['comment_count'];?>）
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
<?php
$this->extend(array(
	'layout' => array(
		'foot'
	))
);
?>