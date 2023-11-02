<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Zodream\Helpers\Str;
/** @var $this View */
$this->title = '商品列表';
?>
<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$this->text($keywords)?>">
            </div>
            <div class="input-group">
                <label>分类</label>
                <select name="cat_id" class="form-control">
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
                <select name="brand_id" class="form-control">
                    <option value="">请选择</option>
                    <?php foreach($brand_list as $item):?>
                    <option value="<?=$item->id?>" <?=$brand_id == $item['id'] ? 'selected': '' ?>><?=$item->name?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="input-group">
                <label>排序</label>
                <select name="sort" class="form-control">
                    <option value="">请选择</option>
                    <?php foreach(['is_best' => '精品', 'is_hot' => '热门', 'is_new' => '新品'] as $key => $label):?>
                    <option value="<?=$key?>"<?=$key === $sort ? ' selected' : ''?>><?=$label?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <div class="btn-group pull-right">
            <a class="btn btn-success" href="<?=$this->url('./@admin/goods/create')?>">新增商品</a>
            <a class="btn btn-info" href="<?=$this->url('./@admin/goods/trash')?>">回收站</a>
        </div>
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
                    <div class="btn-group">
                        <?php if($item->type == 1):?>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/goods/card', ['id' => $item->id])?>">卡密</a>
                        <?php endif;?>
                        <a class="btn btn-info" href="<?=$this->url('./@admin/goods/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/goods/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if($model_list->isEmpty()):?>
        <div class="page-empty-tip">
            空空如也~~
        </div>
    <?php endif;?>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>
</div>