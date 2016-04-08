<?php
use Infrastructure\HtmlExpand;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	))
);
$product = $this->get('product');
?>
<div class="ms-Grid">
	<div class="ms-Grid-row">
		<div class="cover-slider">
            <ul>
                <?php foreach ($this->get('options', array()) as $item) {?>
                    <li><?php echo $item['value'];?></li>
                <?php } ?>
            </ul>
		</div>
	</div>
</div>
<div class="zd-container">
<div class="ms-Grid">
	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md8">
			<h3 class="headTitle">最新产品</h3>
            <ul class="ms-List">
                <li class="ms-ListItem text-center">
                    <img src="<?php echo $product['image'];?>">
                    <h2 class="ms-ListItem-primaryText"><?php echo $product['title'];?></h2>
                    <div class="ms-ListItem-tertiaryText">
                        <?php echo $product['description'];?>
                        <a class="ms-Button ms-Button--primary" href="<?php $this->url('product/view/id/'.$product['id']);?>">查看</a>
                    </div>
                </li>
           </ul>
		</div>
		<div class="ms-Grid-col ms-u-md4">
			<h4 class="headTitle">最新动态</h4>
			<ul class="ms-List">
                <li class="ms-ListItem">
                    <div class="ms-ListItem-primaryText">123</div>
                    <div class="ms-ListItem-actions">
                        <div class="ms-ListItem-action">查看</div>
                    </div>
                </li>
           </ul>
		</div>
	</div>

	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md8">
			<h3 class="headTitle">最受欢迎的博客</h3>
			<ul class="ms-List">
                <?php foreach ($this->get('hotblogs', array()) as $item) {?>
                   <li class="ms-ListItem ms-ListItem--image">
                        <div class="ms-ListItem-image" style="background-color: #767676;">&nbsp;</div>
                        <span class="ms-ListItem-primaryText">
                            <a class="ms-Link" href="<?php $this->url('blog/view/id/'.$item['id']);?>">
                                <?php echo $item['title'];?>
                            </a>
                        </span>
                        <span class="ms-ListItem-tertiaryText"><?php echo $item['description']?></span>
                        <span class="ms-ListItem-metaText"><?php echo $item['create_at']?></span>
                        <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
                </li>
                <?php }?>
           </ul>
		</div>
		<div class="ms-Grid-col ms-u-md4">
			<iframe width="100%" height="450" class="share_self"  frameborder="0" scrolling="no" src="http://widget.weibo.com/weiboshow/index.php?language=&width=0&height=450&fansRow=1&ptype=0&speed=0&skin=2&isTitle=1&noborder=0&isWeibo=1&isFans=0&uid=2911585280&verifier=dcb475f3&dpc=1"></iframe>
		</div>
	</div>

	<div class="ms-Grid-row">
        <div class="ms-Grid-col ms-u-md12">
            <h3 class="headTitle">产品展示</h3>
            <div class="wmuGallery slider">  
            <div class="wmuSlider">  
            <div class="wmuSliderWrapper">
                <ul>
                    <?php foreach ($this->get('hotproducts', array()) as $item) {?>
                        <li>
                            <a href="<?php $this->url('product/view/id/'.$item['id']);?>">
                                <img src="<?php echo $item['image']?>"/>
                            </a>
                        </li>
                    <?php }?>
                </ul>
            </div>
            </div>
            </div>
         </div>
	</div>
</div>
</div>
<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        function() {?>
<script>
System.import("jquery/jquery.wmuSlider").then(function() {
   $('.cover-slider').wmuSlider({
    slide: 'li'
    }); 
    $('.slider').wmuSlider({
        slide: 'li',
        animation: 'slide',  
        items: 4,
        paginationControl: false,
        slideshowSpeed: 4000
    }); 
});
</script>
    <?php }
    )
);
?>