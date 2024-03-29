<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Zodream\Helpers\Str;
use Module\Exam\Domain\Model\QuestionModel;
/** @var $this View */
$this->title = '试卷列表';

?>
<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$this->text($keywords)?>">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/page/create')?>">新增试卷</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>试卷名</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item):?>
            <tr>
                <td><?=$item['id']?></td>
                <td class="left" title="<?=$item['name']?>">
                    <?=Str::substr($item['name'], 0, 20, true)?>
                </td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-default" href="<?=$this->url('./@admin/page/evaluate', ['id' => $item['id']])?>">查看</a>
                        <a class="btn btn-info" href="<?=$this->url('./@admin/page/edit', ['id' => $item['id']])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/page/delete', ['id' => $item['id']])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if($items->isEmpty()):?>
        <div class="page-empty-tip">
            空空如也~~
        </div>
    <?php endif;?>
    <div align="center">
        <?=$items->getLink()?>
    </div>
</div>