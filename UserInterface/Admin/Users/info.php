<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head',
		'navbar'
	))
);
$data = $this->get('user');
?>
<div id="page-wrapper">
  <div class="graphs">
    <div class="xs">
    <h3><?php echo $data['name'];?></h3>
	<div class="tab-content">
		<div class="row">
			<div class="col-sm-2">ID：</div>
			<div class="col-sm-10">
				<?php echo $data['id'];?>
			</div>
			<div class="col-sm-2">用户名：</div>
			<div class="col-sm-10">
				<?php echo $data['name'];?>
			</div>
			<div class="col-sm-2">邮箱：</div>
			<div class="col-sm-10">
				<?php echo $data['email'];?>
			</div>
			<div class="col-sm-2">权限：</div>
			<div class="col-sm-10">
				<?php echo $data['role'];?>
			</div>
			<div class="col-sm-2">注册时间：</div>
			<div class="col-sm-10">
				<?php echo TimeExpand::format($data['create_at']);?>
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
	))
);
?>