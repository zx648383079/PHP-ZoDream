<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */
$this->extend('layout/header');
?>
<div class="page-header">
    <ul class="path">
        <li>
            <a>首页</a>
        </li>
        <li class="active">
            列表
        </li>
    </ul>
    <div class="title">
        列表
    </div>
</div>

<div>
    <form method="POST" class="form-horizontal ajax-form" action="<?=$this->url(['blog/update_term'])?>">
        分类： <input type="text" name="name" placeholder="分类" required>
        关键字： <input type="text" name="keywords">
        <button type="submit" class="btn">增加</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>分类</th>
        <th>关键字</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody class="page-body">
        <?php foreach ($term_list as $value) :?>
            <tr>
                <form method="POST" class="ajax-form" action="<?=$this->url(['blog/update_term'])?>">
                    <td><?=$value['id'];?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $value['id'];?>">
                        <input type="text" name="name" value="<?=$value['name'];?>" placeholder="分类" required>
                    </td>
                    <td>
                        <input type="text" name="keywords" placeholder="关键字" value="<?=$value['keywords'];?>">
                    </td>
                    <td>
                        <button type="submit" class="btn">修改</button>
                        <a href="#" class="btn">删除</a>
                    </td>
                </form>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
</div>
<?php $this->extend('layout/footer'); ?>