<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '月流水';

$this->extend('layouts/header');
?>

    <a class="btn btn-success" href="<?=$this->url('./income/add_log')?>">新增项目</a>
    <hr/>

    <div>
        <h2>月流水</h2>
        <select class="form-control" id="type" name="type1" style="width:100px;float: right;">
            <option value="">全部</option>
            <option value="1">收入</option>
            <option value="0">支出</option>
        </select>
        <div class="col-xs-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <td>时间</td>
                    <td>金额(元)</td>
                    <td>备注</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($log_list as $item): ?>
                    <tr class="<?=$item->type !=1 ? 'danger' : ''?>">
                        <td><?=$item->created_at?></td>
                        <td>
                            <?php if ($item->type !=1):?>
                            <button type='button' class='btn btn-danger btn-xs'>支出</button>
                            <?php endif;?>
                            <?=$item->number;?>
                        </td>
                        <td>
                            <?=$item->remark;?></td>
                        <td>
                            <a class="btn btn-danger" data-type="post" href="<?=$this->url('./income/del_log', ['id' => $item->id])?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach?>
                </tbody>
            </table>
        </div>
    </div>
    <div align="center">
        <?=$log_list->getLink()?>
    </div>

<?php
$this->extend('layouts/footer');
?>