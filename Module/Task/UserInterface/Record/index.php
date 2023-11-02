<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '工作统计';
$js = <<<JS
bindRecord();
JS;
$this->registerJs($js);
?>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" action="<?=$this->url('./record/chart')?>" role="form">
            <div class="input-group">
                <input class="form-control" id="today" type="text" name="date" placeholder="日期">
            </div>
            <div class="input-group">
                <select class="form-control" name="type">
                    <?php foreach(['week' => '按周', 'month' => '按月'] as $key => $item):?>
                    <?php if($key === $type):?>
                    <option value="<?=$key?>" selected><?=$item?></option>
                    <?php else:?>
                    <option value="<?=$key?>"><?=$item?></option>
                    <?php endif;?>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="input-group">
                <select class="form-control" name="chart">
                    <?php foreach(['table' => '列表', 'chart' => '图表'] as $key => $item):?>
                    <option value="<?=$key?>"><?=$item?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
            <div class="input-group">
                <input type="checkbox" name="ignore" value="1"> 忽略无效
            </div>
        </form>
    </div>

    <div class="chart-box"></div>
</div>