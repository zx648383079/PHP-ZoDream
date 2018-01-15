<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '生活预算';

$this->extend('layouts/header');
?>

    <div>
        <h2>增加预算</h2>
        <form data-type="ajax" action="<?=$this->url('./budget/save')?>" method="post" class="form-horizontal" role="form">
            <div class="input-group">
                <label>名称</label>
                <input name="name" type="text" class="form-control" size="16" value="" placeholder="请输入名称" />
            </div>
            <div class="input-group">
                <label>预算(元)</label>
                <input name="budget" type="text" class="form-control" value="1000" />
            </div>
            <div class="input-group">
                <label>已花费(元)</label>
                <input name="spent" type="text" class="form-control" value="0" />
            </div>
            <button type="submit" class="btn btn-success">确认提交</button>
        </form>
    </div>
    <div>
        <h2>生活预算</h2>
        <div class="col-xs-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <td>名称</td>
                    <td>预算(元)</td>
                    <td>已花费(元)</td>
                    <td>剩余(元)</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($model_list as $item): ?>
                    <tr class="<?=$item->spent  <= 0 ? 'danger' : ''?>">
                        <td><?=$item->name?></td>
                        <td>
                            <?=$item->budget;?>
                        </td>
                        <td>
                            <?=$item->spent;?>
                        </td>
                        <td>
                            <?=$item->remain;?>
                        </td>
                        <td>
                            <a class="btn btn-danger" data-type="post" href="<?=$this->url('./budget/delete', ['id' => $item->id])?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach?>
                </tbody>
            </table>
        </div>
    </div>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>

<?php
$this->extend('layouts/footer');
?>