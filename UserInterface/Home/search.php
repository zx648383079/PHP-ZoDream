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
<div class="blog">
			<?php foreach ($page->getPage() as $key => $value) {
						echo $key % 2 == 0 ? '<div class="top-blog">' : null;
				?>
				<div class="col-md-6 blog-top">
					<h3><a href="<?php $this->url('posts/id/'.$value['id']);?>"><?php echo $value['title'];?></a></h3>
					<h6>发表于 <?php echo TimeExpand::format($value['cdate']);?> 作者 <a href="#"><?php echo $value['user'];?></a></h6>
					<a href="<?php $this->url('posts/id/'.$value['id']);?>"><img class="img-responsive" style="height: 230px;width: 500px" src="<?php echo HtmlExpand::getImage($value['content']);?>" alt=" "></a>
					<p><?php echo HtmlExpand::shortString($value['content']);?></p>
					<a href="<?php $this->url('posts/id/'.$value['id']);?>" class="read">阅读全文</a>
				</div>
			<?php 
						echo $key % 2 == 1 || $key + 1 == $page->getPageCount() ? '<div class="clearfix"> </div></div>' : null;
			}?>
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