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
$data = $this->get('message');
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
		<div class="col-sm-2">姓名：</div>
		<div class="col-sm-10">
			<?php echo $data['name'];?>
		</div>
		<div class="col-sm-2">邮箱：</div>
		<div class="col-sm-10">
		<?php echo $data['email'];?>
		</div>
		<div class="col-sm-2">标题：</div>
		<div class="col-sm-10">
			<?php echo $data['title'];?>
		</div>
		<div class="col-sm-2">内容：</div>
		<div class="col-sm-10">
			<?php echo $data['content'];?>
		</div>
		<div  class="col-sm-2">IP：</div>
		<div class="col-sm-10">
			<?php echo $data['ip'];?>
		</div>
		<div class="col-sm-2">是否阅读：</div>
		<div class="col-sm-10">
			已阅
		</div>
		<div class="col-sm-2">提交时间：</div>
		<div class="col-sm-10">
			<?php echo TimeExpand::format($data['cdate']);?>
		</div>
	</div>



	<div class="row">
        	<div class="Compose-Message">               
                <div class="panel panel-default">
                    <div class="panel-heading">
                        回复
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-info">
                            Please fill details to send a new message
                        </div>
                        <hr>
                        <label>Enter Recipient Name : </label>
                        <input type="text" class="form-control1 control3">
                        <label>主题 :  </label>
                        <input type="text" class="form-control1 control3">
                        <label>消息 : </label>
                        <textarea rows="6" class="form-control1 control2"></textarea>
                        <hr>
                        <a href="#" class="btn btn-warning btn-warng1"><span class="glyphicon glyphicon-envelope tag_02"></span> 发送邮件 </a>&nbsp;
                      <a href="#" class="btn btn-success btn-warng1"><span class="glyphicon glyphicon-tags tag_01"></span> Save To Drafts </a>
                    </div>
                 </div>
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