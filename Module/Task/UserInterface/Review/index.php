<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '工作记录';
$js = <<<JS
bindReview();
JS;
$this->registerJs($js);
?>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" action="<?=$this->url('./review/view')?>" role="form">
            <div class="input-group">
                <input class="form-control" id="today" type="text" name="date" placeholder="日期">
            </div>
            <div class="input-group">
                <select class="form-control" name="type">
                    <?php foreach(['day' => '按天', 'week' => '按周', 'month' => '按月'] as $key => $item):?>
                    <?php if($key === $type):?>
                    <option value="<?=$key?>" selected><?=$item?></option>
                    <?php else:?>
                    <option value="<?=$key?>"><?=$item?></option>
                    <?php endif;?>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
    </div>

    <div class="review-box"></div>
</div>