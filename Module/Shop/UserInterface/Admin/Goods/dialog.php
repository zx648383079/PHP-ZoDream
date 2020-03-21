<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php if(!$simple):?>
<div class="dialog-goods-search">
    <form class="form-horizontal" action="<?=$this->url(false, false)?>" role="form">
        <div class="input-group">
            <label class="sr-only" for="keywords">标题</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$keywords?>">
        </div>
        <div class="input-group">
            <label>分类</label>
            <select name="cat_id">
                <option value="0">请选择</option>
                <?php foreach($cat_list as $item):?>
                <option value="<?=$item['id']?>" <?=$cat_id == $item['id'] ? 'selected': '' ?>>
                    <?php if($item['level'] > 0):?>
                        ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                    <?php endif;?>
                    <?=$item['name']?>
                </option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <label>品牌</label>
            <select name="brand_id">
                <option value="0">请选择</option>
                <?php foreach($brand_list as $item):?>
                <option value="<?=$item->id?>" <?=$brand_id == $item['id'] ? 'selected': '' ?>><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <input type="checkbox" name="just_selected" value="1" <?=$just_selected ? 'checked' : ''?>>只显示选中
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
        <input type="hidden" name="simple" value="1">
    </form>
</div>
<div class="dialog-goods-box">
<?php endif;?>
    <div class="goods-list">
        <?php foreach($model_list as $item):?>
        <a href="javascript:;" title="<?=$item->name?>" class="item<?=in_array($item->id, $selected) ? ' selected' : ''?>" data-id="<?=$item->id?>" data-thumb="<?=$item->thumb?>" data-price="<?=$item->price?>">
            <div class="thumb">
                <img src="<?=$item->thumb?>" alt="">
            </div>
            <div class="name"><?=$item->name?></div>
            <div class="price"><?=$item->price?></div>
        </a>
        <?php endforeach;?>
    </div>
    <div class="dialog-pager">
        <?=$model_list->getLink()?>
    </div>
<?php if(!$simple):?>
</div>
<?php endif;?>