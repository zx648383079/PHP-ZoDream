<?php
use Infrastructure\HtmlExpand;
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head'
		))
);
?>
				<div class="product">
					
				<ul id="filters">
						<li class="active"><span class="filter" data-filter="app card icon logo  web">所有</span></li>
						<li><span class="filter" data-filter="app">应用</span></li>
						<li><span class="filter" data-filter="card">插件</span></li>
						<li><span class="filter" data-filter="icon">主题</span></li>
						<li><span class="filter" data-filter="logo">资源</span></li>
					</ul>
					<?php foreach ($this->get('products', array()) as $value) {?>
						<div class="portfolio icon mix_all" data-cat="icon" style="display: inline-block; opacity: 1;">
						<div class="portfolio-wrapper">		
							<a href="<?php $this->url('posts/id/'.$value['id']);?>" class="b-link-stripe b-animate-go  thickbox play-icon ">
						    <img class="img-responsive" src="<?php echo HtmlExpand::getImage($value['content']);?>" alt=""  />
						    <ul class="social-ic">
								<li class="down"><span> </span></li>
							</ul>
							</a>
		                </div>
					</div>	
					<?php }?>	

					
					<div class="clearfix"> </div>
					</div>	
					

				</div>


</div>	
<?php
$this->extend(array(
		'layout' => array(
				'foot'
		)), array(
            '@home/js' => array(
                'jquery.mixitup.min'
            ),
            function() {?>
			<script type="text/javascript">
					$(function () {
						
						var filterList = {
						
							init: function () {
							
								// MixItUp plugin
								// http://mixitup.io
								$('#portfoliolist').mixitup({
									targetSelector: '.portfolio',
									filterSelector: '.filter',
									effects: ['fade'],
									easing: 'snap',
									// call the hover effect
									onMixEnd: filterList.hoverEffect()
								});				
							
							},
							
							hoverEffect: function () {
							
								// Simple parallax effect
								$('#portfoliolist .portfolio').hover(
									function () {
										$(this).find('.label').stop().animate({bottom: 0}, 200, 'easeOutQuad');
										$(this).find('img').stop().animate({top: -30}, 500, 'easeOutQuad');				
									},
									function () {
										$(this).find('.label').stop().animate({bottom: -40}, 200, 'easeInQuad');
										$(this).find('img').stop().animate({top: 0}, 300, 'easeOutQuad');								
									}		
								);				
							
							}
				
						};
						
						// Run the show!
						filterList.init();
						
						
					});	
					</script>
        <?php }
        )
);
?>