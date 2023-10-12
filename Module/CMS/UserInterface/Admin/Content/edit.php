<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $model->id > 0 ? '文章编辑' : '新增文章';
?>

<h1><?=$this->title?></h1>
<form data-type="ajax" action="<?=$this->url('./@admin/content/save')?>" method="post" class="form-table" role="form">
    <div class="tab-box">
        <div class="tab-header">
            <?php foreach($tab_list as $item):?>
            <div class="tab-item<?=$item['active'] ? ' active' : ''?>">
                <?=$item['name']?>
            </div>
            <?php endforeach;?>
        </div>
        <div class="tab-body">
        <?php foreach($tab_list as $group):?>
            <div class="tab-item<?=$group['active'] ? ' active' : ''?>">
                <?php foreach($group['items'] as $item):?>
                    <?=$scene->toInput($item, $data)?>
                    <?php if($item['field'] === 'title'):?>
                    <div class="input-group">
                        <label>栏目</label>
                        <select name="cat_id">
                            <?php foreach($cat_menu as $item):?>
                            <?php if($item['model_id'] == $model->id):?>
                            <option value="<?=$item['id']?>" <?=$cat_id == $item['id'] ? 'selected': '' ?>>
                                <?php if($item['level'] > 0):?>
                                    ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                                <?php endif;?>
                                <?=$item['title']?>
                            </option>
                            <?php endif;?>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
            <?php endforeach;?>
        </div>
    </div>

    <div class="btn-group">
        <?php if(empty($data['status']) || $data['status'] != 5):?>
        <button type="submit" class="btn btn-success">确认保存</button>
        <?php endif;?>
        <button type="button" class="btn btn-info" data-type="publish">保存并发布</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
    <input type="hidden" name="id" value="<?=$id?>">
    
    <input type="hidden" name="model_id" value="<?=$model->id?>">
    <input type="hidden" name="parent_id" value="<?=$data['parent_id']?>">
</form>
