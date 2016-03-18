<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    ))
);
$post = $this->get('data', array(
    'title' => null,
    'image' => null,
    'keyword' => null,
    'description' => null,
    'category_id' => 1,
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
                            <label for="focusedinput" class="col-sm-2 control-label">内容</label>
                            <div class="col-sm-8">
                                <textarea id="editor" name="content" class="" placeholder="关键字"><?php echo $post['content'];?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">图片</label>
                            <div class="col-sm-8">
                                <input type="text" name="image" value="<?php echo $post['image'];?>" class="form-control1" id="focusedinput" placeholder="图片">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">关键字</label>
                            <div class="col-sm-8">
                                <input type="text" name="keyword" value="<?php echo $post['keyword'];?>" class="form-control1" id="focusedinput" placeholder="关键字">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">说明</label>
                            <div class="col-sm-8">
                                <input type="text" name="description" value="<?php echo $post['description'];?>" class="form-control1" id="focusedinput" placeholder="说明">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">分类</label>
                            <div class="col-sm-8">
                                <select class="form-control1" name="category_id" required>
                                    <?php foreach($this->get('category', array()) as $value) {?>
                                        <option value="<?php echo $value['id'];?>"<?php if ($value['id'] == $post['category_id']) {?> selected<?php }?>><?php echo $value['name'];?></option>
                                    <?php }?>
                                </select>
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