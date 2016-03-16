<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head',
                'navbar'
		)), array(
            '@admin/css' => array(
                'custom.css'
            )
        )
);
$data = $this->get('post');
?>
<div id="page-wrapper">
  <div class="graphs">
    <div class="xs">
    <h3><?php echo $data['title'];?></h3>
	<div class="tab-content">
		<div class="row">
			<div class="col-sm-2">ID：</div>
			<div class="col-sm-10">
				<?php echo $data['id'];?>
			</div>
			<div class="col-sm-2">标题：</div>
			<div class="col-sm-10">
				<?php echo $data['title'];?>
			</div>
			<div class="col-sm-2">类型：</div>
			<div class="col-sm-10">
				<?php echo $data['kind'];?>
			</div>		
			<div  class="col-sm-2">图片：</div>
			<div class="col-sm-10">
				<img src="<?php $this->asset($data['image']);?>" alt="">
			</div>
			<div class="col-sm-2">内容：</div>
			<div class="col-sm-10">
				<?php echo $data['content'];?>
			</div>
			<div class="col-sm-2">提交时间：</div>
			<div class="col-sm-10">
				<?php echo TimeExpand::format($data['cdate']);?>
			</div>
		</div>
         <div class="clearfix"> </div>
   </div>
    <div class="copy_layout">
         <p>Copyright &copy; 2015.ZoDream All rights reserved.</p>
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
            '@admin/js' => array(
                'metisMenu.min',
                'custom'
            )
        )
);
?>