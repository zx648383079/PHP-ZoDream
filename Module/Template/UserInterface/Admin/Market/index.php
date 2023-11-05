
<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
/** @var $this View */
$this->title = '组件市场';
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
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <?php foreach($items as $item):?>
        <div class="col-md-6">
            <div class="large-item">
                <div class="item-cover">
                    <img src="<?= $item['thumb'] ?>" alt="<?= $item['name'] ?>">
                    <div class="item-mask">
                        <a class="btn btn-primary">Preview</a>
                        <a class="btn btn-success">Add To</a>
                    </div>
                </div>
                <div class="item-body">
                    <div class="item-name"><?= $item['name'] ?></div>
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