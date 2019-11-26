<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Zodream\Helpers\Str;
/** @var $this View */
$this->title = '商品回收站列表';
?>
<div class="search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label class="sr-only" for="keywords">标题</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$keywords?>">
        </div>
        <div class="input-group">
            <label>分类</label>
            <select name="cat_id">
                <option value="">请选择</option>
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
                <option value="">请选择</option>
                <?php foreach($brand_list as $item):?>
                <option value="<?=$item->id?>" <?=$brand_id == $item['id'] ? 'selected': '' ?>><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <a class="btn btn-success pull-right" data-type="del" data-tip="确认全部删除？" href="<?=$this->url('./@admin/goods/clear')?>">全部清空</a>
    <a class="btn btn-success pull-right" data-type="del" data-tip="确认全部还原？" href="<?=$this->url('./@admin/goods/restore')?>">全部还原</a>
</div>

<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>商品名</th>
        <th class="auto-hide">分类</th>
        <th class="auto-hide">品牌</th>
        <th>价格</th>
        <th class="auto-hide">推荐</th>
        <th>销量</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td>
                <div class="goods-info">
                    <div class="thumb">
                        <img src="<?=$item->thumb?>" alt="" width="60" height="60">
                    </div>
                    <a href="<?=$this->url('./goods', ['id' => $item->id])?>" target="_blank">
                    <?=Str::substr($item->name, 0, 20, true)?></a>
                </div>
            </td>
            <td class="auto-hide">
                <?php if ($item->category):?>
                    <a href="<?=$this->url('./@admin/goods', ['cat_id' => $item->cat_id])?>">
                        <?=$item->category->name?>
                    </a>
                <?php else:?>
                [未分类]
                <?php endif;?>
            </td>
            <td class="auto-hide">
                <?php if ($item->brand):?>
                    <a href="<?=$this->url('./@admin/goods', ['brand_id' => $item->brand_id])?>">
                        <?=$item->brand->name?>
                    </a>
                <?php else:?>
                [无]
                <?php endif;?>
            </td>
            <td>
                <?=$item->price?>
            </td>
            <td class="auto-hide">
                <?php foreach(['is_best' => '精品', 'is_hot' => '热门', 'is_new' => '新品'] as $key => $label):?>
                <div class="toggle-box<?=$item[$key] ? ' active' : ''?>" data-type="toggle" data-url="<?=$this->url('./@admin/goods/toggle', ['id' => $item->id, 'name' => $key])?>"><?=$label?><i class="fa"></i></div>
                <?php endforeach;?>
            </td>
            <td>
                <?=$item->sales?>
            </td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" data-type="del" data-tip="确认还原此商品？" href="<?=$this->url('./@admin/goods/restore', ['id' => $item->id])?>">还原</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/goods/delete', ['id' => $item->id, 'trash' => true])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>