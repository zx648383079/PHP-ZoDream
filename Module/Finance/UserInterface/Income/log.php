<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '月流水';
$js = <<<JS
bindLog();
JS;
$this->registerJs($js);
?>
<div class="search-box">
    <div class="pull-right">
        <a class="btn btn-success" href="<?=$this->url('./income/add_log')?>">新增流水</a>
        <a class="btn btn-success" href="<?=$this->url('./income/add_day_log')?>">一日三餐</a>
        <a class="btn btn-success" href="<?=$this->url('./income/batch_edit_log')?>">批量编辑</a>
        <a class="btn btn-success" data-type="import" href="javascript:;" data-url="<?=$this->url('./income/import')?>">导入</a>
        <a class="btn btn-success" href="<?=$this->url('./income/export')?>">导出</a>
    </div>
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <input class="form-control" type="text" name="keywords" placeholder="关键字" value="<?=$this->text($keywords)?>">
        </div>
        <div class="input-group">
            <select class="form-control" name="type">
                <?php foreach([0 => '全部', 2 => '收入', 1 => '支出', 3 => '借出', 4 => '借入'] as $key => $item):?>
                <?php if($type == $key):?>
                    <option value="<?=$key?>" selected><?=$item?></option>
                <?php else:?>
                    <option value="<?=$key?>"><?=$item?></option>
                <?php endif;?>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <select class="form-control" name="account">
                <option value="0">选择账户</option>
                <?php foreach($account_list as $item):?>
                <?php if($account == $item['id']):?>
                    <option value="<?=$item['id']?>" selected><?=$item['name']?></option>
                <?php else:?>
                    <option value="<?=$item['id']?>"><?=$item['name']?></option>
                <?php endif;?>
                <?php endforeach;?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
        <div class="input-group">
            <input type="checkbox" name="edit">  编辑模式
        </div>
    </form>
</div>

<hr/>

<div>
    <h2>月流水</h2>
    <table id="log-table" class="table table-hover">
        <thead>
        <tr>
            <td>时间</td>
            <td>账户</td>
            <td>金额(元)</td>
            <td>备注</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach($log_list as $item): ?>
            <tr class="<?=$item->type !=1 ? 'danger' : ''?>">
                <td><?=$item->happened_at?></td>
                <td>
                    <?php if($item->account):?>
                        <?=$item->account->name?>
                    <?php endif;?>
                </td>
                <td class="money-td">
                    <?php if ($item->type != 1):?>
                    <button type='button' class='btn btn-danger btn-xs'>
                    <?= ['支出', '收入', '借出', '借入'][$item->type] ?>
                    </button>
                    <?php endif;?>
                    <span><?=$item->money?></span>
                </td>
                <td>
                    <?=$item->remark?></td>
                <td>
                    <div class="no-edit">
                        <a class="btn btn-default" href="<?=$this->url('./income/add_log', ['clone_id' => $item->id])?>">克隆</a>
                        <?php if($item->type == 2):?>
                            <a class="btn btn-default" href="<?=$this->url('./income/add_log', ['parent_id' => $item->id])?>">还款</a>
                            <a class="btn btn-default" href="<?=$this->url('./income/add_log', ['parent_id' => $item->id])?>">坏账</a>
                        <?php endif;?>
                        <?php if($item->type == 3):?>
                            <a class="btn btn-default" href="<?=$this->url('./income/add_log', ['parent_id' => $item->id])?>">还款</a>
                        <?php endif;?>
                    </div>
                    <div class="on-edit">
                        <a class="btn btn-default" href="<?=$this->url('./income/edit_log', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./income/delete_log', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach?>
        </tbody>
    </table>
</div>
<div align="center">
    <?=$log_list->getLink()?>
</div>