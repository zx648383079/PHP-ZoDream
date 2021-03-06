<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Zodream\Helpers\Str;
use Module\Exam\Domain\Model\QuestionModel;
/** @var $this View */
$this->title = '试卷评估列表';

?>
<div class="page-search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label class="sr-only" for="keywords">用户名</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="会员名" value="<?=$this->text($keywords)?>">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
        <input type="hidden" name="id" value="<?=$page->id?>">
    </form>
</div>

<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>会员名</th>
        <th>耗时(分)</th>
        <th>正确(个)</th>
        <th>错误(个)</th>
        <th>得分</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td>
                <?=$item->user->name?>
            </td>
            <td><?=$item->spent_time?></td>
            <td><?=$item->right?></td>
            <td><?=$item->wrong?></td>
            <td><?=$item->score?></td>
            <td><?=$item->status > 0 ? '已完成' : '答卷中'?></td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/page/question', ['id' => $item->id])?>">查看</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/page/delete_evaluate', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>