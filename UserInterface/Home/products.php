<?php
use Infrastructure\HtmlExpand;
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	))
);
$page = $this->get('page');
?>
<div class="zd-container">
<div class="ms-Grid off">
	<div class="ms-Grid-row">
        <div class="ms-Grid-col ms-u-md12">
            <ul class="ms-Pivot ms-Pivot--tabs ms-Pivot--large">
                <li class="ms-Pivot-link is-selected">全部</li>
                <?php foreach ($this->get('classes', array()) as $item) {?>
                    <li class="ms-Pivot-link"><?php echo $item['name'];?></li>
                <?php }?>
            </ul>
        </div>
        <?php foreach ($page->getPage() as $value) {?>
            <div class="ms-Grid-col ms-u-md3">
                <div class="zd-ListItem">
                    <img class="zd-image" src="<?php echo $value['image'];?>">
                    <h2 class="zd-title" class="ms-ListItem-primaryText">
                        <a class="ms-Link" href="<?php $this->url('product/view/id/'.$value['id']);?>">
                            <?php echo $value['title'];?>
                        </a>
                    </h2>
                    <p class="zd-text">
                        <?php echo $value['description'];?>
                    </p>
                </div>
            </div>
        <?php }?>
	</div>
    <div class="ms-Grid-row">
        <div class="ms-Grid-col ms-u-md12">
            <?php $page->pageLink();?>
        </div>
    </div>
</div>
</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        'jquery.Pivot',
        function(){ ?>
 <script type="text/javascript">
 $('.ms-Pivot').Pivot();
 </script>
     <?php }
    )
);
?>