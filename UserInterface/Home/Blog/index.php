<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Infrastructure\HtmlExpand;
$this->extend(array(
	'layout' => array(
		'head'
	))
);
$page = $this->get('data');
?>
<div class="container">
    <ul>
        <li><a href="<?php $this->url('blog');?>">全部</a></li>
        <?php foreach ($this->get('term', array()) as $item) {?>
            <li><a href="<?php $this->url(null, array('term' => $item['id']));?>"><?php echo $item['name'];?></a></li>
        <?php }?>
    </ul>
    <div>
        <ul>
            <?php foreach ($page->getPage() as $item) {?>
                <li>
                    <span>
                        <a href="<?php $this->url('blog/view/id/'.$item['id']);?>">
                            <?php echo $item['title'];?>
                        </a>
                    </span>
                    <span><?php echo $item['keyword'];?></span>
                    <span><?php echo $item['description'];?></span>
                    <span><?php echo $item['create_at'];?></span>
                </li>
            <?php }?>
        </ul>
        <div>
            <?php $page->pageLink();?>
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