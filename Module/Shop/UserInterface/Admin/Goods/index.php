<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
/** @var $this View */
$this->title = '商品列表';
?>
<div class="search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label class="sr-only" for="keywords">标题</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <a class="btn btn-success pull-right" href="<?=$this->url('./admin/goods/create')?>">新增商品</a>
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
                <a href="<?=$this->url('./goods', ['id' => $item->id])?>" target="_blank"><?=$item->name?></a>
            </td>
            <td class="auto-hide">
                <?php if ($item->category):?>
                    <a href="<?=$this->url('./admin/goods', ['cat_id' => $item->cat_id])?>">
                        <?=$item->category->name?>
                    </a>
                <?php else:?>
                [未分类]
                <?php endif;?>
            </td>
            <td class="auto-hide">
                <?php if ($item->brand):?>
                    <a href="<?=$this->url('./admin/goods', ['brand_id' => $item->brand_id])?>">
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
                <div class="toggle-box<?=$item[$key] ? ' active' : ''?>" data-type="toggle" data-url="<?=$this->url('./admin/goods/toggle', ['id' => $item->id, 'name' => $key])?>"><?=$label?><i class="fa"></i></div>
                <?php endforeach;?>
            </td>
            <td>
                <?=$item->sales?>
            </td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./admin/goods/edit', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/goods/delete', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>