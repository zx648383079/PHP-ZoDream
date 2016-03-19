<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
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
		<div class="ms-Grid-col ms-u-md2">
			<ul class="ms-List">
				<li class="ms-ListItem"><a class="ms-Link">全部</a></li>
				<?php foreach ($this->get('category', array()) as $item) {?>
					<li class="ms-ListItem"><a class="ms-Link"><?php echo $item['name'];?></a></li>
				<?php }?>
			</ul>
		</div>
		<div class="ms-Grid-col ms-u-md10">
			<ul class="ms-List">
				<?php foreach ($page->getPage() as $item) {?>
					<li class="ms-ListItem ms-ListItem--image">
						<div class="ms-ListItem-image" style="background-color: #767676;">&nbsp;</div>
						<span class="ms-ListItem-primaryText">
							<a class="ms-Link" href="<?php $this->url('blog/view/id/'.$item['id']);?>">
								<?php echo $item['title'];?>
							</a>
						</span>
						<span class="ms-ListItem-secondaryText"><?php echo $item['keyword'];?></span>
						<span class="ms-ListItem-tertiaryText"><?php echo $item['description'];?></span>
						<span class="ms-ListItem-metaText"><?php echo $item['create_at'];?></span>
						<div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
						<div class="ms-ListItem-actions">
							<div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
							<div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
							<div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
							<div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
						</div>
					</li>
				<?php }?>
			</ul>
		</div>
		<div class="ms-Grid-col ms-u-md10">
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