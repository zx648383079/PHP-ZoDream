<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Zodream\Helpers\Str;
use Module\Exam\Domain\Model\QuestionModel;
/** @var $this View */
$this->title = '题目列表';

?>
<div class="page-search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label class="sr-only" for="keywords">标题</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$keywords?>">
        </div>
        <div class="input-group">
            <label>科目</label>
            <select name="course">
                <option value="">请选择</option>
                <?php foreach($cat_list as $item):?>
                <option value="<?=$item['id']?>" <?=$course == $item['id'] ? 'selected': '' ?>>
                    <?php if($item['level'] > 0):?>
                        ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                    <?php endif;?>
                    <?=$item['name']?>
                </option>
                <?php endforeach;?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/question/create')?>">新增题目</a>
</div>

<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>题目</th>
        <th class="auto-hide">科目</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td title="<?=$item->title?>">
                【<?=QuestionModel::$type_list[$item['type']]?>】
                <?=Str::substr($item->title, 0, 20, true)?>
            </td>
            <td class="auto-hide">
                <?php if ($item->course):?>
                    <a href="<?=$this->url('./@admin/question', ['course' => $item->course_id])?>">
                        <?=$item->course->name?>
                    </a>
                <?php else:?>
                [-]
                <?php endif;?>
            </td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/question/edit', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/question/delete', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>