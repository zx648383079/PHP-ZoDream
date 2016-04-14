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
$sub = $this->get('sub', array());
$page = $this->get('page');
?>
<div class="container">
    <?php if (!empty($sub))?>
    <div>
        <div>子版块</div>
        <?php foreach ($sub as $item) { ?>
            <div>
                <div><?php echo $item['name'];?></div>
            </div>
        <?php }?>
    </div>
    <?php }?>
    
    
    <div class="row">
        <ul>
            <?php foreach ($page->getPage() as $item) {?>
                <li>
                <span>
                    <a href="<?php $this->url('blog/view/id/'.$item['id']);?>">
                        <?php echo $item['title'];?>
                    </a>
                </span>
                    <span><?php echo $item['user_name'];?></span>
                    <span><?php $this->ago($item['create_at']);?></span>
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