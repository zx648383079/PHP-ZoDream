
<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
/** @var $this View */
$this->title = '站点组件';
?>


<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$this->text($keywords)?>">
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
                    <div class="btn-group">
                        <?php if($item['type'] < 1):?>
                        <a class="btn btn-info" >Create Page</a>
                        <?php endif;?>
                        <a class="btn btn-danger" >Delete</a>
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