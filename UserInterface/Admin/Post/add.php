<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
    'layout' => array(
        'head'
    )), array(
        'zodream/add.css'
    )
);
$post = $this->get('data', array(
    'title' => null,
    'term_id' => 1,
    'content' => null
));
?>
<h3>新建</h3>
<div>
    <form method="POST">
        <div class="left">
            <input type="hidden" name="id" value="<?php $this->ech('id');?>">
        标题：<input type="text" name="title" value="<?php echo $post['title'];?>" placeholder="标题"></br>
        内容：<textarea id="editor" name="content" placeholder="内容"><?php echo $post['content'];?></textarea>
        </div>
        <div class="right">
            分   类： <select name="term_id" required>
                <?php foreach($this->get('term', array()) as $value) {?>
                    <option value="<?php echo $value['id'];?>"<?php if ($value['id'] == $post['term_id']) {?> selected<?php }?>><?php echo $value['name'];?></option>
                <?php }?>
            </select></br>
          公开度： <select name="status">
              <option value="publish">公开</option>
              <option value="password">密码保护</option>
              <option value="private">私密</option>
              <option value="draft">草稿</option>
          </select></br>
          <input type="password" name="password" value="" placeholder="密码"></br>
            <button type="submit">保存</button>
        </div>
    </form>
</div>


<?php 
$this->extend(array(
    'layout' => array(
        'foot'
    )), array(
        function(){?>
<script type="text/javascript">
require(['admin/add']);
</script>
			<?php }
        )
);
?>