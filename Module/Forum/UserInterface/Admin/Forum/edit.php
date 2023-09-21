<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '板块';
$js = <<<JS
bindForum();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/forum/save')?>
<div class="tab-box">
        <div class="tab-header">
            <div class="tab-item active">
                基本设置
            </div><div class="tab-item">
                主题分类
            </div>
        </div>
        <div class="tab-body">
            <div class="tab-item active">
                <?=Form::text('name', true)?>
                <div class="input-group">
                    <label>上级</label>
                    <select name="parent_id">
                        <option value="0">-- 无上级板块 --</option>
                        <?php foreach($forum_list as $item):?>
                        <option value="<?=$item['id']?>" <?=$model->parent_id == $item['id'] ? 'selected': '' ?>>
                            <?php if($item['level'] > 0):?>
                                ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                            <?php endif;?>
                            <?=$item['name']?>
                        </option>
                        <?php endforeach;?>
                    </select>
                </div>
                <?=Form::file('thumb')?>
                <?=Form::textarea('description')?>
                <?=Form::text('position')?>
            </div>
            <div class="tab-item">
                <table id="classify-box">
                    <thead>
                    <tr>
                        <th>名称</th>
                        <th>图片</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($classify_list as $item):?>
                    <tr>
                        <td>
                            <input type="text" name="classify[name][]" value="<?=$item->name?>">
                            <input type="hidden" name="classify[id][]" value="<?=$item->id?>">
                        </td>
                        <td>
                            <input type="text" name="classify[icon][]" value="<?=$item->icon?>">
                        </td>
                        <td>
                            <input type="text" name="classify[position][]" value="<?=$item->position?>" size="4">
                        </td>
                        <td>
                            <a href="javascript:;" class="del">删除</a>
                        </td>
                    </tr>
                    <?php endforeach;?>
                    <tr>
                        <td>
                            <input type="text" name="classify[name][]">
                            <input type="hidden" name="classify[id][]" value="0">
                        </td>
                        <td>
                            <input type="text" name="classify[icon][]">
                        </td>
                        <td>
                            <input type="text" name="classify[position][]" size="4">
                        </td>
                        <td>
                            <a href="javascript:;" class="del">删除</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <a href="javascript:;" class="btn add-classify">添加主题分类</a>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>