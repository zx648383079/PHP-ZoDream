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
$post = $this->get('post', array(
		'title' => null,
		'kind' => null,
		'content' => null
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
                            <label for="focusedinput" class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-8">
                                <input type="text" name="title" value="<?php echo $post['title'];?>" class="form-control1" id="focusedinput" placeholder="标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">类型</label>
                            <div class="col-sm-8">
                                <select class="form-control1" name="kind" required>
                                	<option value="1"<?php if (1 == $post['kind']) {?> selected<?php }?>>服务预览</option>
					                <option value="2"<?php if (2 == $post['kind']) {?> selected<?php }?>>产品</option>
					                <option value="3"<?php if (null == $post['kind'] || 3 == $post['kind']) {?> selected<?php }?>>博客</option>
					                <option value="4"<?php if (4 == $post['kind']) {?> selected<?php }?>>下载</option>
					              </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">内容</label>
                            <div class="col-sm-8">
                                <textarea id="editor" name="content" class="" placeholder="关键字"><?php echo $post['content'];?></textarea>
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