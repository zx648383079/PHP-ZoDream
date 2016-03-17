<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head',
		'navbar'
	)), array(
		'@ueditor/third-party/SyntaxHighlighter/shCoreDefault.css'
	)
);
$data = $this->get('single');
?>
<div id="page-wrapper">
  <div class="graphs">
    <div class="xs">
    <h3><?php echo $data['name'];?></h3>  
    <a class="btn btn-default" href="<?php $this->url('singles');?>">返回</a>  
    <a class="btn btn-primary" href="<?php $this->url('singles/edit/id/'.$data['id']);?>">编辑</a>
	<div class="tab-content">
		<div class="row">
			<div class="col-sm-2">ID：</div>
			<div class="col-sm-10">
				<?php echo $data['id'];?>
			</div>
			<div class="col-sm-2">页面：</div>
			<div class="col-sm-10">
				<?php echo $data['name'];?>
			</div>
			<div class="col-sm-2">内容：</div>
			<div class="col-sm-10">
				<?php echo $data['value'];?>
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
		'@ueditor/third-party/SyntaxHighlighter/shCore',
		function () {?>
<<script type="text/javascript">
$(function(){
	SyntaxHighlighter.all();
});
</script>
			<?php }
        )
);
?>