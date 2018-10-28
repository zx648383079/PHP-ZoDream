<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '文档:'.$model->name;
?>

<div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <h1>文档主页 </h1>
                    <div class="opt-btn">
                        <a href="<?=$this->url('./admin/page/edit', ['id' => $model->id])?>" class="btn btn-default"><i class="fa fa-fw fa-edit"></i>编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/page/delete', ['id' => $model->id])?>"><i class="fa fa-fw fa-times"></i>删除</a>

                    </div>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="col-lg-12">
            <?=$model->content?>
        </div>
</div>