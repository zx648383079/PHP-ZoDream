<?php
use Infrastructure\HtmlExpand;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	))
);
$data = $this->get('data', array());
$product = $this->get('product');
?>
<div class="ms-Grid">
	<div class="ms-Grid-row">
		<div style="height: 300px;background-color: #0D414A;">

		</div>
	</div>
	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md8">
			<h3>最新产品</h3>
			<video id="my-video" class="video-js" controls preload="auto" width="100%" height="366"
				   data-setup="{}">
				<source src="<?php echo HtmlExpand::getVideo($product['content']); ?>" type='video/mp4'>
				<p class="vjs-no-js">
					To view this video please enable JavaScript, and consider upgrading to a web browser that
					<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
				</p>
			</video>
			<h2><?php echo $product['title'];?></h2>
			<p><?php echo HtmlExpand::shortString($product['content'], 300);?></p>
		</div>
		<div class="ms-Grid-col ms-u-md4">
			<h4>最新动态</h4>
			jjjjj
			<div class="lear">
				<a href="<?php $this->url('services');?>" class="link">更多</a>
			</div>
		</div>
	</div>

	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md8">
			<h3>最受欢迎的博客</h3>
			<?php foreach ($this->get('blog', array()) as $key => $value) {?>
				<div class="popular-top">
					<div class="pop-im">
						<img src="<?php echo HtmlExpand::getImage($value['content']);?>" class="img-responsive" alt="" />
					</div>
					<div class="pop-dat">
						<h4><?php echo $value['title'];?></h4>
						<p><?php echo HtmlExpand::shortString($value['content']);?></p>
						<ul class="links">
							<li><i class="date"> </i><span class="icon_text"><?php echo TimeExpand::format($value['cdate']);?></span></li>
							<li><a href="#"><i class="comt"> </i><span class="icon_text"><?php echo $value['comment_count']?>条评论</span></a></li>
						</ul>
					</div>
					<div class="clearfix"> </div>
				</div>
			<?php }?>
		</div>
		<div class="ms-Grid-col ms-u-md4">
			<iframe width="100%" height="450" class="share_self"  frameborder="0" scrolling="no" src="http://widget.weibo.com/weiboshow/index.php?language=&width=0&height=450&fansRow=1&ptype=0&speed=0&skin=2&isTitle=1&noborder=0&isWeibo=1&isFans=0&uid=2911585280&verifier=dcb475f3&dpc=1"></iframe>
		</div>
	</div>

	<div class="ms-Grid-row">
		<h3>产品展示</h3>
		<ul id="flexiselDemo3">
			<?php foreach ($this->get('download', array()) as $value) {?>
				<li>
					<div class="team1">
						<div class="tm-left">
							<img src="<?php echo HtmlExpand::getImage($value['content']);?>" class="img-responsive" alt="" />
						</div>
						<div class="tm-right">
							<h5><?php echo $value['title'];?></h5>
							<p><?php echo HtmlExpand::shortString($value['content'], 50);?><a href="<?php $this->url('posts/id/'.$value['id'])?>">更多</a></p>
							<p>标签: 应用, 程序</p>
							<p>分类: <a href="#">安全</a></p>
						</div>
						<div class="clearfix"> </div>
					</div>
				</li>
			<?php }?>
		 </ul>
	</div>
</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	))
);
?>