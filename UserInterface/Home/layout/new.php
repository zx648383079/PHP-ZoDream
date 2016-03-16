<?php
use Infrastructure\HtmlExpand;
?>
<div class="latest-news">
				<div class="row">
					<header>
						<h3>最新动态</h3>
					</header>
				</div>
					<?php foreach ($this->get('news', array()) as $value) {?>
           				<div class="col-md-4 grid_7">
           				<div class="element">
							<img src="<?php echo HtmlExpand::getImage($value['content']);?>" alt="">
							<h4><?php echo $value['title'];?></h4>
							<p><?php echo HtmlExpand::shortString($value['content']);?> </p>
						</div>
						</div>
               		<?php }?>
					<div class="clearfix"> </div>
		</div>