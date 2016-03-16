	<!-- footer -->
		<div class="footer">
			<div class="container">
			<div class="footer-top">
				<div class="foot-left">
					<h5><a href="<?php $this->url('/');?>">ZoDream</a></h5>
				</div>
				<div class="foot-right">
					<ul>
						<li><a href="#"><i class="fb"></i></a></li>
						<li><a href="#"><i class="twt"></i></a></li>
						<li><a href="#"><i class="in"></i></a></li>
						<li><a href="#"><i class="rss"></i></a></li>
						<div class="clearfix"> </div>
					</ul>
				</div>
				<div class="clearfix"> </div>
			</div>
			<i class="line"> </i>
			<div class="footer-bottom">
				<div class="foot-left">
					<div class="foot-nav">
					<ul>
						<li><a href="<?php $this->url('/');?>">首页</a></li> |
						<li<?php $this->cas($this->hasUrl('about'), ' class="active"');?>><a href="<?php $this->url('about');?>">关于</a></li> |
						<li<?php $this->cas($this->hasUrl('services'));?>><a href="<?php $this->url('services');?>">服务</a></li> |
						<li<?php $this->cas($this->hasUrl('product'));?>><a href="<?php $this->url('product');?>">产品</a></li> |
						<li<?php $this->cas($this->hasUrl('blog'));?>><a href="<?php $this->url('blog');?>">博客</a></li> |
						<li<?php $this->cas($this->hasUrl('task'));?>><a href="<?php $this->url('task');?>">任务</a></li>
						<li<?php $this->cas($this->hasUrl('download'));?>><a href="<?php $this->url('download');?>">下载</a></li> |
						<li<?php $this->cas($this->hasUrl('contact'));?>><a href="<?php $this->url('contact');?>">联系</a></li>
							<div class="clearfix"></div>
					</ul>
					</div>					
				</div>
				<div class="foot-right">
					<p>Copyright &copy; 2015-2018 ZoDream.cn All rights reserved.</p>
				</div>
				<div class="clearfix"> </div>
			</div>
			<div class="foot-center">
				<a href="http://www.miitbeian.gov.cn/" target="_blank">湘ICP备16003508号</a>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!-- footer -->
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<?php $this->jcs(array(
	'jquery.min',
    function() {?>
<script charset="gbk" src="http://www.baidu.com/js/opensug.js"></script>
<script>
    $( "span.menu" ).click(function() {
        $( ".head-nav ul" ).slideToggle(300, function() {
        // Animation complete.
        });
    });
</script>
     <?php }
));?>
    </body>
</html>
