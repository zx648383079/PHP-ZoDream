<?php
use Infrastructure\HtmlExpand;
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head'
		))
);
?>
<div class="content text-center">
<div class="services_overview">
       		<h3>服务预览</h3>
       		<?php foreach ($this->get('page', array()) as $key => $value) {?>
           <div class="col-md-4 service_grid <?php echo $key % 3 != 2 ? : 'span_55';?>">
       			<img src="<?php echo HtmlExpand::getImage($value['content']);?>" class="img-responsive" alt=""/>
       			<h4><?php echo $value['title'];?></h4>
       			<p><?php echo HtmlExpand::shortString($value['content']);?></p>
       		</div>
               <?php }?>
			<div class="clearfix"></div>
       </div> 	
	  <?php $this->extend(array(
          'layout' => array(
              'new'
      )));?>
		</div>
</div>	
<?php
$this->extend(array(
		'layout' => array(
				'foot'
		))
);
?>