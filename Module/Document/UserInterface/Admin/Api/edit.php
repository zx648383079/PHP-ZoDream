<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = $model->id ?  '编辑接口:'.$model->name : '新建接口';
$url = $this->url('./admin/api/', false);
$js = <<<JS
    editApi('{$url}', {$model->id});
JS;
$this->registerJs($js);
?>

<h1><?=$this->title?></h1>
<?= Form::open($model, './admin/api/save') ?>
    <?= Form::text('name', true) ?>
    <?= Form::select('parent_id', [$tree_list, [0 => '-- 顶级 --']])?>

    <div class="extent-box" <?=$model->parent_id < 1 ? ' style="display:none"' : ''?>>
        <?= Form::select('method', [$model->method_list])?>
        <?= Form::text('uri') ?>
        <?= Form::textarea('description') ?>


        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default" data-kind="3">
                    <div class="panel-heading">
                        Haader参数
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">

                            <div class="panel-header">
                            <table id="table-3" class="table table-striped table-bordered table-hover table-wrap">
                                <thead>
                                <tr class="success">

                                    <th>字段键</th>
                                    <th style="width: 35%">字段值</th>
                                    <th>备注说明</th>
                                    <th>快捷操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $this->extend('./headerRow', compact('header_fields'));?>
                                </tbody>
                            </table>
                            </div>

                            <div class="form-group">
                                <a href="javascript:;" class="btn btn-default" data-action="add"><i class="fa fa-plus"></i>添加参数</a>
                                <a href="javascript:;" data-action="import"  class="btn btn-default"><i class="fa fa-random"></i>自动匹配数据</a>
                            </div>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-6 -->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default" data-kind="1">
                    <div class="panel-heading">
                        请求参数
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">

                            <div class="panel-request">
                            <table id="table-1" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr class="success">

                                    <th>字段别名</th>
                                    <th>字段含义</th>
                                    <th>字段类型</th>
                                    <th>是否必填</th>
                                    <th>默认值</th>
                                    <th>备注说明</th>
                                    <th>快捷操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $this->extend('./requestRow', compact('request_fields'));?>
                                </tbody>
                            </table>
                            </div>

                            <div class="form-group">
                                <a href="javascript:;"  data-action="add" class="btn btn-default"><i class="fa fa-plus"></i>添加参数</a>
                                <a href="javascript:;"  data-action="import" class="btn btn-default"><i class="fa fa-random"></i>自动匹配数据</a>
                            </div>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-6 -->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default" data-kind="2">
                    <div class="panel-heading">
                        响应参数
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <div class="panel-response">
                            <table id="table-2" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr class="success">
                                    <th>字段别名</th>
                                    <th>字段含义</th>
                                    <th>字段类型</th>
                                    <th>MOCK规则</th>
                                    <th>备注说明</th>
                                    <th>快捷操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $this->extend('./responseRow', compact('response_fields'));?>
                                </tbody>
                            </table>
                            </div>

                            <div class="form-group">
                                <a href="javascript:;"  data-action="add" class="btn btn-default"><i class="fa fa-plus"></i>添加参数</a>
                                <a href="javascript:;"  data-action="import" class="btn btn-default"><i class="fa fa-random"></i>自动匹配数据</a>
                            </div>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-6 -->
        </div>

    </div>


    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="project_id" value="<?=$model->project_id?>">
<?= Form::close('id') ?>
