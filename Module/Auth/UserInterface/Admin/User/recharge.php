<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;

/** @var $this View */
$this->title = '账户充值';
?>

<h1><?=$this->title?></h1>
<?=Form::open('./admin/user/recharge_save')?>
    <div class="input-group">
        <label>账号</label>
        <div>
            <?=$user->name?>
        </div>
    </div>
    <div class="input-group">
        <label>账户余额</label>
        <div>
            <?=$user->money?>
        </div>
    </div>
    <div class="input-group">
        <label>金额</label>
        <div>
            <select name="type">
                <option value="0">+</option>
                <option value="1">-</option>
            </select>
            <input type="text" name="money" reuired placeholder="选择金额">
        </div>
    </div>
    <div class="input-group">
        <label>备注</label>
        <div>
            <textarea rows="10" name="remark" required></textarea>
        </div>
    </div>
    <button type="submit" class="btn btn-success">确认执行</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消执行</a>
    <input type="hidden" name="user_id" value="<?=$user->id?>">
<?= Form::close() ?>