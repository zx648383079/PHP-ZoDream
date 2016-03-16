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
		
<!-- banner -->	
	<div class="banner">
		<div class="wmuSlider slider section" id="section-1">
			<?php foreach ($data as $value) {?>
				<article style="position: absolute; width: 100%; opacity: 0;">
					<div class="banner-info">
						<?php echo $value['value'];?>
					</div>
				</article>
			<?php }?>
		 </div>		
		
		
	</div>
<!-- banner -->	
<!-- feature -->
<div class="feature">
	<div class="col-md-8 feature-left">
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
	<div class="col-md-4 our-right">
		<h4>我们的服务</h4>
		<?php foreach ($this->get('service', array()) as $key => $value) {?>
       		<img style="height: 140px;" src="<?php echo HtmlExpand::getImage($value['content']);?>" class="img-responsive" alt=""/>
       		<h5><?php echo $value['title'];?></h5>
       		<p><?php echo HtmlExpand::shortString($value['content'], 50);?></p>
         <?php }?>
		<div class="lear">
			<a href="<?php $this->url('services');?>" class="link">更多</a>
		</div>
	</div>
	<div class="clearfix"> </div>
</div>	
<!-- feature -->
<!-- popular -->
<div class="popular">
	<div class="col-md-8 popular-left">
		<div class="twe-top">
			<div class="twe">
				<h3>最受欢迎的博客</h3>
			</div>
			<div class="twe-le">
			<ul>
				<li><h6><a href="#">订阅</a></h6></li>
				<li><a href="#"><i class="rs"></i></a></li>
				<li><h6><a href="<?php $this->url('blog');?>">更多</a></h6></li>
			</ul>
			</div>
			<div class="clearfix"> </div>
		</div>
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
	<div class="col-md-4 twets">
		<iframe width="100%" height="450" class="share_self"  frameborder="0" scrolling="no" src="http://widget.weibo.com/weiboshow/index.php?language=&width=0&height=450&fansRow=1&ptype=0&speed=0&skin=2&isTitle=1&noborder=0&isWeibo=1&isFans=0&uid=2911585280&verifier=dcb475f3&dpc=1"></iframe>
	</div>
	<div class="clearfix"> </div>
</div>	

		<!-- downlod -->
	<div class="downlod">
		<div class="dow-top">
			<div class="dow">
				<h3>下载中心</h3>
			</div>
			<div class="dow-le">
				<h6>标签: <a href="<?php $this->url('download');?>"> 全部 </a>, <a href="#"> 主题 </a>, <a href="#"> 插件 </a>, <a href="#"> 应用 </a> </h6>
			</div>
			<div class="clearfix"> </div>
		</div>
			<ul id="flexiselDemo3">
				<?php foreach ($this->get('download') as $value) {?>
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
	<!-- downlod -->
</div>
<!-- popular -->	

<?php
$this->extend(array(
		'layout' => array(
				'foot'
		)), array(
            '@home/js' => array(
                'jquery.wmuSlider',
                'jquery.flexisel'
            ),
            function() {?>
			<script>
       			$('.slider').wmuSlider();         
   		    </script>
            <script type="text/javascript">
							$(window).load(function() {
								
								$("#flexiselDemo3").flexisel({
									visibleItems: 3,
									animationSpeed: 1000,
									autoPlay: false,
									autoPlaySpeed: 3000,    		
									pauseOnHover: true,
									enableResponsiveBreakpoints: true,
									responsiveBreakpoints: { 
										portrait: { 
											changePoint:480,
											visibleItems: 1
										}, 
										landscape: { 
											changePoint:640,
											visibleItems: 2
										},
										tablet: { 
											changePoint:768,
											visibleItems: 3
										}
									}
								});
								
							});
							</script>
        <?php }
        )
);
?>