<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Infrastructure\HtmlExpand;
$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	))
);
$page = $this->get('page');
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul>
                <li><a href="<?php $this->url('blog');?>">全部</a></li>
                <?php foreach ($this->get('term', array()) as $item) {?>
                    <li><a href="<?php $this->url(null, array('termid' => $item['id']));?>"><?php echo $item['name'];?></a></li>
                <?php }?>
            </ul>
        </div>
        <div class="col-md-9">
            <ul>
                <?php foreach ($page->getPage() as $item) {?>
                    <li>
                    <span>
                        <a href="<?php $this->url('blog/view/id/'.$item['id']);?>">
                            <?php echo $item['title'];?>
                        </a>
                    </span>
                        <span><?php echo $item['term'];?></span>
                        <span><?php echo $item['user'];?></span>
                        <span><?php $this->ago($item['create_at']);?></span>
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