<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Document\Domain\Model\FieldModel;
/** @var $this View */
$this->title = '接口:'.$api->name;
$content = json_encode($response_json);
$js = <<<JS
refreshJson({$content});
JS;
$this->registerJs($js, View::JQUERY_READY);
?>

<div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <h1>接口主页 </h1>
                    <div class="btn-group opt-btn">
                        <a href="<?=$this->url('./@admin/api/edit', ['id' => $api->id])?>" class="btn btn-default"><i class="fa fa-fw fa-edit"></i>编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/api/delete', ['id' => $api->id])?>"><i class="fa fa-fw fa-times"></i>删除</a>
                        <a href="<?=$this->url('./@admin/api/debug', ['id' => $api->id])?>" class="btn btn-default"><i class="fa fa-fw fa-wrench"></i>调试</a>

                    </div>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-header">
                        接口详情
                    </div>
                    <div class="panel-body">
                        <p class="text-muted"><label>接口名称：</label><?=$api->name?></p>
                        <p class="text-muted"><label>所属项目：</label><?=$project->name?></p>
                        <p class="text-muted"><label>请求类型：</label><?=$api->method?></p>
                        <p class="text-muted"><label>接口描述：</label><?=$api->description?></p>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-header">
                        接口地址
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">

                                <tbody>
                                <?php foreach($project->environment as $item):?>
                                <tr>
                                    <td style="width: 20%;"><?=$item['title']?>(<?=$item['name']?>)</td>
                                    <td style="width: 50%;"><code><?=$api->getUri($item['domain'])?></code></td>
                                    <td style="width: 15%;">
                                        <button type="button" class="btn btn-success btn-copy" data-clipboard-text="<?=$api->getUri($item['domain'])?>"><i class="fa fa-fw fa-copy"></i>复制链接</button>
                                    </td>
                                </tr>
                                <?php endforeach;?>

                                <tr>
                                    <td style="width: 20%;">模拟环境(mock)</td>
                                    <td style="width: 50%;"><code><?=$this->url('./api/mock', ['id' => $api->id])?></code></td>
                                    <td style="width: 15%;">
                                        <button type="button" class="btn btn-success btn-copy" data-clipboard-text="<?=$this->url('./api/mock', ['id' => $api->id])?>"><i class="fa fa-fw fa-copy"></i>复制链接</button>
                                    </td>

                                </tr>

                                </tbody>
                            </table>

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
                <div class="panel panel-default">
                    <div class="panel-header">
                        Header参数
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr class="success">
                                    <th>字段键</th>
                                    <th>字段值</th>
                                    <th>备注说明</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($header_fields as $item):?>
                                <tr>
                                    <td><?=$item['name']?></td>
                                    <td><?=$item['default_value']?></td>
                                    <td><?=$item['remark']?></td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
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
                <div class="panel panel-default">
                    <div class="panel-header">
                        请求参数
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr class="success">
                                    <th>字段别名</th>
                                    <th>字段含义</th>
                                    <th>字段类型</th>
                                    <th>是否必填</th>
                                    <th>默认值</th>
                                    <th>备注说明</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($request_fields as $item):?>
                                <tr>
                                    <td><?=$item['name']?></td>
                                    <td><?=$item['title']?></td>
                                    <td><?=FieldModel::$type_list[$item['type']]?></td>
                                    <td><?=$item['is_required'] ? '是' : '否'?></td>
                                    <td><?=$item['default_value']?></td>
                                    <td><?=$item['remark']?></td>
                                </tr>
                                <?php endforeach;?>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-6 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-header">
                        响应参数
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr class="success">
                                    <th>字段别名</th>
                                    <th>字段含义</th>
                                    <th>字段类型</th>
                                    <th>MOCK规则</th>
                                    <th>备注说明</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($response_fields as $item):?>
                                <tr class="<?=$item['parent_id'] < 1 ? 'warning' : ''?>">

                                    <td style="text-align: left;padding-left: 50px;">
                                        <?php if($item['level'] > 0):?>
                                            <span>ￂ<?=str_repeat('ｰ', $item['level'] - 1)?></span>
                                        <?php endif;?>
                                        <?=$item['name']?></td>
                                    <td><?=$item['title']?></td>
                                    <td><?=FieldModel::$type_list[$item['type']]?></td>

                                    <td><?=$item['mock']?></td>

                                    <td><?=$item['remark']?></td>

                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-6 -->
        </div>
        <!-- /.row -->
        <div class="panel-json">
        <div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-header">
                返回示例<a role="button" href="javascript:refreshMock('<?=$this->url('./@admin/api/mock', ['id' => $api->id])?>');" class="btn fa fa-sync" title="刷新数据" ></a>
            </div>
            <div class="panel-body">
                <div class="json-box">
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
</div>