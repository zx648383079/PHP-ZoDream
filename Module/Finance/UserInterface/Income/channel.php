<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '消费渠道';

$this->extend('layouts/header');
?>

    <div>
        <h2>增加消费渠道</h2>
        <form data-type="ajax" action="<?=$this->url('./income/save_channel')?>" method="post" class="form-horizontal" role="form">
            <div class="input-group">
                <label>名称</label>
                <input name="name" type="text" class="form-control" size="16" value="" placeholder="请输入名称" />
            </div>
            <button type="submit" class="btn btn-success">确认提交</button>
        </form>
    </div>
    <div>
        <h2>消费渠道列表</h2>
        <div class="col-xs-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <td>名称</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($model_list as $item): ?>
                    <tr>
                        <td><?=$item->name?></td>
                        <td>
                            <a class="btn btn-danger" data-type="post" href="<?=$this->url('./income/delete_chanel', ['id' => $item->id])?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach?>
                </tbody>
            </table>
        </div>
    </div>

<?php
$this->extend('layouts/footer');
?>