<?php
use Zodream\Template\View;
/** @var $this View */
?>
<div class="theme-showcase" style="width: 400px;">
    <form data-type="ajax" action="<?=$this->url('./money/save_earnings')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label for="name">配置项目</label>
            <input type="text" class="form-control" id="name" value="<?=$model->name?>" readonly style="width:50%" />
        </div>
        <div class="input-group">
            <label for="money">资金</label>
            <input type="text" class="form-control" id="money" value="<?=$model->money?>" readonly style="width:50%" />
        </div>
        <div class="input-group">
            <label for="earnings_number">实现收益</label>
            <input type="text" class="form-control" name="money" id="earnings_number" placeholder="实现收益" onkeyup="value=value.replace(/[^\d.]/g,'')" style="width:50%" />
        </div>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>
</div>