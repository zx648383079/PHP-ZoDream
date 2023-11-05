
<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
/** @var $this View */
$this->title = '我的组件';
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
                <select name="category" class="form-control">
                    <option value="">请选择</option>
                    <?php foreach($cat_list as $item):?>
                    <option value="<?=$item['id']?>" <?=$category == $item['id'] ? 'selected': '' ?>>
                        <?php if($item['level'] > 0):?>
                            ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                        <?php endif;?>
                        <?=$item['name']?>
                    </option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="input-group">
                <label>类型</label>
                <select name="type" class="form-control">
                    <?php foreach($type_list as $key => $item):?>
                    <option value="<?= $key ?>" <?=$type == $key ? 'selected': '' ?>><?= $item ?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <div class="btn-group pull-right">
            <a href="" class="btn btn-success">导入</a>
            <a href="<?= $this->url('./@admin/market/my_edit') ?>" class="btn btn-primary">新建</a>
        </div>
    </div>
</div>
<div class="container-fluid list-table">
    <div class="row">
        <?php foreach($items as $item):?>
        <div class="image-row-item">
            <div class="item-cover">
                <img src="<?= $item['thumb'] ?>" alt="<?= $item['name'] ?>">
            </div>
            <div class="item-body">
                <div class="item-title">
                    <span class="no"><?= $item['id'] ?></span>
                    <?= $item['name'] ?></div>
                <div class="item-meta">
                    <p><?= $item['description'] ?></p>
                </div>
                <div class="item-footer">
                    <div class="item-meta-bar">
                        <?php if($item['category']):?>
                        <a class="category"><i
                            class="fa fa-bookmark"></i><b><?= $item['category']['name'] ?></b></a>
                        <?php endif;?>
                        
                        <span><i class="fa fa-eye"></i><b>
                            <?= $item['status'] > 0 ? 'Reviewed' : 'Pending' ?></b>
                        </span>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-info" href="<?= $this->url('./@admin/market/my_edit', ['id' => $item['id']]) ?>">Edit</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/market/my_delete', ['id' => $item['id']])?>">Delete</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <?= Theme::emptyTooltip($items) ?>
    <div align="center">
        <?=$items->getLink()?>
    </div>
</div>