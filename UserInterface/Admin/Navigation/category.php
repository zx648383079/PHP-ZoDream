<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/head');
?>
<div class="row">
    <form method="POST">
        <h4 class="col-xs-1 text-center">
            分类：
        </h4>
         <div class="col-xs-6">
             <input type="text" class="form-control" name="name" placeholder="分类" required>
         </div>
        <h4 class="col-xs-1 text-center">
            顺序：
        </h4>
        <div class="col-xs-2">
            <input type="number" class="form-control" name="position" value="0">
        </div>
        <div class="col-xs-1">
            <button type="submit" class="btn btn-primary">增加</button>
        </div>
    </form>
</div>

<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>分类</th>
        <th>顺序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $value) :?>
            <tr>
                <form method="POST">
                    <td><?=$value['id']?></td>
                    <td>
                        <input type="hidden" name="id" value="<?=$value['id']?>">
                        <input type="text" class="form-control" name="name" value="<?=$value['name']?>" placeholder="分类" required>
                    </td>
                    <td>
                        <input type="number" class="form-control" name="position" value="<?=$value['position']?>">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary">修改</button>
                        <?=Html::a('删除', ['navigation/deleteCategory', 'id' => $value['id']], ['class' => 'btn btn-danger'])?>
                    </td>
                </form>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>


<?=$this->extend('layout/foot')?>