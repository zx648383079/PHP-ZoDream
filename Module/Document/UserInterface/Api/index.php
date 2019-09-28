<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '接口：'.$api->name;
$this->set('current_id', $api->id);
$content = json_encode($response_json);
$js = <<<JS
refreshJson({$content});
JS;
$this->registerJs($js, View::JQUERY_READY);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="page-header">
            <h1><?=$this->title?> </h1>
            <div class="opt-btn">
                <a href="javascript:;" data-action="code" class="btn btn-default"><i class="fa fa-fw fa-wrench"></i>代码</a>
            </div>
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="zd-panel panel-default">
            <div class="zd-panel-head">
                接口详情
            </div>
            <div class="zd-panel-body">
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
        <div class="zd-panel panel-default">
            <div class="zd-panel-head">
                接口地址
            </div>
            <!-- /.panel-heading -->
            <div class="zd-panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">

                        <tbody>
                        <?php foreach($project->environment as $item):?>
                        <tr>
                            <td style="width: 20%;"><?=$item['title']?>(<?=$item['name']?>)</td>
                            <td style="width: 50%;"><code><?=$api->getUri($item['domain'])?></code></td>
                            <td style="width: 15%;">
                                <button type="button" class="btn btn-xs btn-success btn-copy" data-clipboard-text="<?=$api->getUri($item['domain'])?>"><i class="fa fa-fw fa-copy"></i>复制链接</button>
                            </td>
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
        <div class="zd-panel panel-default">
            <div class="zd-panel-head">
                Header参数
            </div>
            <!-- /.panel-heading -->
            <div class="zd-panel-body">
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
        <div class="zd-panel panel-default">
            <div class="zd-panel-head">
                请求参数
            </div>
            <!-- /.panel-heading -->
            <div class="zd-panel-body">
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
                            <td><?=$item->type_list[$item->type]?></td>
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
        <div class="zd-panel panel-default">
            <div class="zd-panel-head">
                响应参数
            </div>
            <!-- /.panel-heading -->
            <div class="zd-panel-body">
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

                            <td style="text-align: left;padding-left: 50px;"><?=$item['parent_id'] ? '└' : ''?><?=$item['name']?></td>
                            <td><?=$item['title']?></td>
                            <td><?=$item->type_list[$item->type]?></td>

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
            <div class="zd-panel panel-default">
                <div class="zd-panel-head">
                    返回示例</a>
                </div>
                <div class="zd-panel-body">
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

<div id="coder-dialog" class="dialog dialog-box" data-type="dialog">
    <div class="dialog-header">
        <form action="<?=$this->url('./api/code', ['id' => $api->id], false)?>" method="post">
            <select name="kind">
                <?php foreach([1 => '响应', 2 => '请求', 0 => '整体'] as $k => $item):?>
                <option value="<?=$k?>"><?=$item?></option>
                <?php endforeach;?>
            </select>
            <select name="lang">
                <?php foreach(['c#', 'go', 'ts', 'dart', 'java', 'kotlin'] as $item):?>
                <option value="<?=$item?>"><?=$item?></option>
                <?php endforeach;?>
            </select>
            <button>生成</button>
            <button type="button" class="dialog-close">关闭</button>
        </form>
    </div>
    <div class="dialog-body">

    </div>
</div>