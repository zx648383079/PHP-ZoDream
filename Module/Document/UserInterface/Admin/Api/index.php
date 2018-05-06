<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->extend('../layouts/header');
?>

<div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <h1>接口主页 </h1>
                    <div class="opt-btn">
                        <a href="<?=$this->url('./admin/api/edit', ['id' => $api->id])?>" class="btn btn-default"><i class="fa fa-fw fa-edit"></i>编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/api/delete', ['id' => $api->id])?>"><i class="fa fa-fw fa-times"></i>删除</a>
                        <a href="<?=$this->url('./admin/api/debug', ['id' => $api->id])?>" class="btn btn-default"><i class="fa fa-fw fa-wrench"></i>调试</a>

                    </div>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        接口详情
                    </div>
                    <div class="panel-body">
                        <p class="text-muted"><label>接口名称：</label><?=$api->name?></p>
                        <p class="text-muted"><label>所属模块：</label>{{$api.module.title}}</p>
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
                    <div class="panel-heading">
                        接口地址
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">

                                <tbody>
                                {{foreach $envs as $env}}
                                {{$env_url = {{$env.domain}}|cat:'/'|cat:{{$api.uri}}}}
                                <tr>
                                    <td style="width: 20%;">{{$env.title}}({{$env.name}})</td>
                                    <td style="width: 50%;"><code>{{$env_url}}</code></td>
                                    <td style="width: 15%;">
                                        <button type="button" data-clipboard-text="{{$env_url}}" class="btn btn-xs btn-success js_copyUrl"><i class="fa fa-fw fa-copy"></i>复制链接</button>
                                    </td>
                                </tr>
                                {{/foreach}}

                                <tr>
                                    <td style="width: 20%;">模拟环境(mock)</td>
                                    <td style="width: 50%;"><code>{{url("mock/{{id_encode($api.id)}}", '', true)}}</code></td>
                                    <td style="width: 15%;">
                                        <button type="button" data-clipboard-text="{{url("mock/{{id_encode($api.id)}}", '', true)}}" class="btn btn-xs btn-success js_copyUrl"><i class="fa fa-fw fa-copy"></i>复制链接</button>
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
                    <div class="panel-heading">
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
                                {{foreach $header_fields as $header_field}}
                                <tr class="{{$header_field.class}}">
                                    <td>{{$header_field.name}}</td>
                                    <td>{{$header_field.default_value}}</td>
                                    <td>{{$header_field.intro}}</td>
                                </tr>
                                {{/foreach}}

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
                    <div class="panel-heading">
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
                                {{foreach $request_fields as $request_field}}
                                <tr class="{{$request_field.class}}">
                                    <td>{{$request_field.delimiter}}{{if $request_field.parent_id}}--{{/if}}{{$request_field.name}}</td>
                                    <td>{{$request_field.title}}</td>
                                    <td>{{\app\field::get_type_list({{$request_field.type}})}}</td>
                                    <td>{{if $request_field.is_required}}是{{else}}否{{/if}}</td>
                                    <td>{{$request_field.default_value}}</td>
                                    <td>{{$request_field.intro}}</td>
                                </tr>
                                {{/foreach}}

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
                    <div class="panel-heading">
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
                                {{foreach $response_fields as $response_field}}
                                <tr class="{{if $response_field.level == 1}}warning{{/if}}">
                                    <td style="text-align: left;padding-left: 50px;">{{$response_field.delimiter}}{{if $response_field.parent_id}}└{{/if}}{{$response_field.name}}</td>
                                    <td>{{$response_field.title}}</td>
                                    <td>{{\app\field::get_type_list({{$response_field.type}})}}</td>
                                    <td>{{$response_field.mock}}</td>
                                    <td>{{$response_field.intro}}</td>
                                </tr>

                                {{/foreach}}
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
            <div class="panel-heading">
                返回示例<a role="button" class="btn fa fa-refresh js_refreshField" data-id="{{$api.id}}" data-toggle="tooltip" title="刷新数据" ></a>
            </div>
            <div class="panel-body">
                <div class="hidden json-data">{{$respose_json}}</div>
                <div class="json-box"></div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
        </div>

<?php
$this->extend('../layouts/footer');
?>