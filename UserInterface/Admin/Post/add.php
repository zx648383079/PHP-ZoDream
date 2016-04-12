<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
$post = $this->get('data', array(
    'title' => null,
    'image' => null,
    'keyword' => null,
    'description' => null,
    'term_id' => 1,
    'content' => null
));
?>
<h3>新建</h3>
<div>
    <form method="POST">
        <input type="hidden" name="id" value="<?php $this->ech('id');?>">
        标题：<input type="text" name="title" value="<?php echo $post['title'];?>" placeholder="标题"></br>
        内容：<textarea id="editor" name="content" placeholder="关键字"><?php echo $post['content'];?></textarea>
        分类：<select name="term_id" required>
                <?php foreach($this->get('term', array()) as $value) {?>
                    <option value="<?php echo $value['id'];?>"<?php if ($value['id'] == $post['term_id']) {?> selected<?php }?>><?php echo $value['name'];?></option>
                <?php }?>
            </select>
        <button type="submit">保存</button>
    </form>
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