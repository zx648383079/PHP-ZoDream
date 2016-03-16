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
$options = $this->get('options');
?>

<div id="page-wrapper">
    <div class="graphs">
        <div class="xs">
            <h3>基本设置</h3>
            <div class="tab-content">
                <div class="tab-pane active" id="horizontal-form">
                    <form class="form-horizontal" action="<?php $this->url();?>" method="POST">
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-8">
                                <input type="text" name="title" value="<?php echo $options['title'];?>" class="form-control1" id="focusedinput" placeholder="标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">Tagline</label>
                            <div class="col-sm-8">
                                <input type="text" name="tagline" value="<?php echo $options['tagline'];?>" class="form-control1" id="focusedinput" placeholder="Tagline">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">关键字</label>
                            <div class="col-sm-8">
                                <input type="text" name="keywords" value="<?php echo $options['keywords'];?>" class="form-control1" id="focusedinput" placeholder="关键字">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">说明</label>
                            <div class="col-sm-8">
                                <input type="text" name="description" value="<?php echo $options['description'];?>" class="form-control1" id="focusedinput" placeholder="说明">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">作者</label>
                            <div class="col-sm-8">
                                <input type="text" name="author" value="<?php echo $options['author'];?>" class="form-control1" id="focusedinput" placeholder="作者">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-push-2 col-sm-3">
                                <button type="submit" class="btn-success btn">保存</button>
                            </div>
                        </div>
            </form>
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