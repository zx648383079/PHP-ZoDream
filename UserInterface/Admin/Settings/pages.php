<?php
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
?>

<div id="page-wrapper">
    <div class="graphs">
        <div class="xs">
            <h3>单页页面更改</h3>
            <div class="tab-content">
            
            	<div class="panel panel-default">
                    <div class="panel-heading">
                       主页轮播内容
                    </div>
                    <div class="panel-body">
                        <textarea rows="6" class="form-control1 control2"></textarea>
                        <hr>
                        <button type="submit" class="btn-success btn">保存</button>
                    </div>
                 </div>
                 
                 <div class="panel panel-default">
                    <div class="panel-heading">
                       关于页面说明
                    </div>
                    <div class="panel-body">
                        <textarea rows="6" class="form-control1 control2"></textarea>
                        <hr>
                        <button type="submit" class="btn-success btn">保存</button>
                    </div>
                 </div>
                 
                 <div class="panel panel-default">
                    <div class="panel-heading">
                       联系页面说明
                    </div>
                    <div class="panel-body">
                        <textarea rows="6" class="form-control1 control2"></textarea>
                        <hr>
                        <button type="submit" class="btn-success btn">保存</button>
                    </div>
                 </div>
        </div>
  </div>
  <div class="copy_layout">
      <p>Copyright &copy; 2015.ZoDream All rights reserved.</p>
  </div>
  </div>
      </div>
      <!-- /#page-wrapper -->
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