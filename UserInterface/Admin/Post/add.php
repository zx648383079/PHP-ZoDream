<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
/** @var $model \Domain\Model\Blog\PostModel */
$this->registerCssFile('zodream/add.css');
$this->registerJs('require(["admin/add"]);');
$this->extend('layout/header');
?>
<h3>新建</h3>
<div>
    <form method="POST">
        <div class="left">
            <input type="hidden" name="id" value="<?=$model->id?>">
        标题：<input type="text" name="title" value="<?=$model->title?>" placeholder="标题"></br>
        内容：<textarea id="editor" name="content" placeholder="内容"><?=$model->content?></textarea>
        </div>
        <div class="right">
            分   类： <select name="term_id" required>
                <?php foreach($term as $value) :?>
                    <option value="<?=$value['id'];?>"<?php if ($value['id'] == $model['term_id']) :?> selected<?php endif;?>><?=$value['name'];?></option>
                <?php endforeach;?>
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


<?=$this->extend('layout/footer')?>