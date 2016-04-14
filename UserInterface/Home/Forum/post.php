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
    <?php foreach ($page->getPage() as $item) {?>
    
    <div class="row">
        <div class="col-md-3">
            
        </div>
        <div class="col-md-9">
            <?php echo $item['content'];?>
            <span><?php $this->ago($item['create_at']);?></span>
            <?php if ($item['first'] == 1) {?>
            点赞
            <?php }?>
        </div>
    </div>
    <?php }?>
    <div class="row">
        <?php $page->pageLink();?>
    </div>
</div>
<?php
$this->extend(array(
	'layout' => array(
		'foot'
	))
);
?>