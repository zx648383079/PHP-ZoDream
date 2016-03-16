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
$post = $this->get('single', array(
		'name' => 'index',
		'value' => null
));
?>

<div id="page-wrapper">
    <div class="graphs">
        <div class="xs">
            <h3>新建</h3>
            <div class="tab-content">
                <div class="tab-pane active" id="horizontal-form">
                    <form class="form-horizontal" action="<?php $this->url();?>" method="POST">
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">页面</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" disabled value="<?php echo $post['name'];?>" class="form-control1" id="focusedinput" placeholder="页面">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">内容</label>
                            <div class="col-sm-8">
                                <textarea id="editor" name="value" style="height: 300px;" class="" placeholder="内容"><?php echo $post['value'];?></textarea>
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
            ),
			'@ueditor' => array(
					'ueditor.config',
					'ueditor.all.min'
			),
			function(){?>
<script type="text/javascript">
UE.getEditor("editor");
</script>
			<?php }
        )
);
?>