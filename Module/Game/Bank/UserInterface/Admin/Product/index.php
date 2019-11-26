<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '投资项目';
?>

    <a class="btn btn-success" href="<?=$this->url('./@admin/product/create')?>">新增项目</a>
    <hr/>
    <div>
        <div class="col-xs-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <td>名称</td>
                    <td>最小投资额</td>
                    <td>周期(天)</td>
                    <td>收益率</td>
                    <td>风险</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($model_list as $item): ?>
                    <tr>
                        <td>
                            <a href="<?=$this->url('./@admin/log', ['product_id' => $item->id])?>">
                                <?=$item->name?>
                            </a>
                        </td>
                        <td>
                            <?=$item->min_amount?>
                        </td>
                        <td>
                            <?=$item->cycle?>
                        </td>
                        <td>
                            <?=$item->earnings?>
                        </td>
                        <td>
                            <?=$item->risk?>
                        </td>
                        <td>
                            <a class="btn btn-primary" href="<?=$this->url('./@admin/product/edit', ['id' => $item->id])?>">编辑</a>
                            <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/product/delete', ['id' => $item->id])?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach?>
                </tbody>
            </table>
        </div>
    </div>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>