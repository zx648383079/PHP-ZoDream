<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$js = <<<JS

JS;
$this->registerJs($js, View::JQUERY_READY);
$this->extend('../layouts/header');
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/api/save')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>接口名称</label>
            <input name="name" type="text" class="form-control" placeholder="项目名称" value="<?=$model->name?>">
        </div>
        <div class="input-group">
            <label>请求类型</label>
            <select name="method">
            <?php foreach(['GET', 'POST', 'PUT', 'DELETE', 'OPTION'] as $item):?>
               <option value="<?=$item?>" <?= $item == $model->method ? 'selected' : '' ?>><?=$item?></option>
            <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <label>接口路径</label>
            <input name="uri" type="text" class="form-control" placeholder="项目名称" value="<?=$model->uri?>">
        </div>
        <div class="input-group">
            <label>接口描述</label>
            <textarea name="description" class="form-control" placeholder="备注信息"><?=$model->description?></textarea>
        </div>
       

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Haader参数
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">

                            <div class="panel-header">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr class="success">

                                    <th>字段键</th>
                                    <th>字段值</th>
                                    <th>备注说明</th>
                                    <th>快捷操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($header_fields as $item):?>
                                <tr>
                                    <td style="width: 20%"><?=$item['name']?></td>
                                    <td style="width: 35%"><?=$item['default_value']?></td>
                                    <td style="width: 35%"><?=$item['remark']?></td>
                                    <td style="width: 10%">
                                        <a href="javascript:void(0);" class="fa fa-pencil js_addHeaderFieldBtn" data-title="编辑header参数" data-id="{{$api.id}}-{{$header_field.id}}"></a>
                                        <a href="javascript:void(0);" class="fa fa-trash-o js_deleteFieldBtn" data-title="删除参数" data-id="{{$header_field.id}}"></a>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-default js_addHeaderFieldBtn" data-title="添加header参数" data-id="{{$api.id}}-0"><i class="fa fa-fw fa-plus"></i>添加参数</button>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        请求参数
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">

                            <div class="panel-request">
                            <table class="table table-striped table-bordered table-hover">
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
                                <?php foreach($request_fields as $item):?>
                                <tr class="{{$request_field.class}}">
                                    <td><?=$item['name']?></td>
                                    <td><?=$item['title']?></td>
                                    <td>{{\app\field::get_type_list({{$request_field.type}})}}</td>
                                    <td><?=$item['is_required'] ? '是' : '否'?></td>
                                    <td><?=$item['default_value']?></td>
                                    <td><?=$item['remark']?></td>
                                    <td style="width: 10%">
                                        <a href="javascript:void(0);" class="fa fa-pencil js_addRequestFieldBtn" data-title="编辑请求参数" data-id="{{$api.id}}-{{$request_field.id}}"></a>
                                        <a href="javascript:void(0);" class="fa fa-trash-o js_deleteFieldBtn" data-title="删除参数" data-id="{{$request_field.id}}"></a>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-default js_addRequestFieldBtn" data-title="添加请求参数" data-id="{{$api.id}}-0"><i class="fa fa-fw fa-plus"></i>添加参数</button>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        响应参数
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <div class="panel-response">
                            <table class="table table-striped table-bordered table-hover">
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
                                <?php foreach($response_fields as $item):?>
                                <tr class="{{if $response_field.level == 1}}warning{{/if}}">

                                    <td style="text-align: left;padding-left: 50px;"><?=$item['parent_id'] ? '└' : ''?><?=$item['name']?></td>
                                    <td><?=$item['title']?></td>
                                    <td>{{\app\field::get_type_list({{$response_field.type}})}}</td>

                                    <td><?=$item['mock']?></td>

                                    <td><?=$item['remark']?></td>

                                    <td style="width: 10%">

                                        <a href="javascript:void(0);" class="btn btn-xs js_addResponseFieldBtn" data-title="编辑响应参数{{$response_field.title}}" data-id="{{$api.id}}-{{$response_field.parent_id}}-{{$response_field.id}}" data-toggle="tooltip" title="编辑响应参数" data-placement="bottom"><i class="fa fa-fw fa-pencil"></i></a>

                                        <a href="javascript:void(0);" class="btn btn-xs js_deleteFieldBtn" data-title="删除参数" data-id="{{$response_field.id}}" data-toggle="tooltip" title="删除响应参数" data-placement="bottom"><i class="fa fa-fw fa-trash-o"></i></a>

                                        <a href="javascript:void(0);" class="btn btn-xs js_addResponseFieldBtn" data-title="添加响应子参数" data-id="{{$api.id}}-{{$response_field.id}}-0" data-toggle="tooltip" title="添加子参数" data-placement="bottom" {{if !in_array($response_field.type, ['array', 'object'])}}disabled{{/if}} ><i class="fa fa-fw fa-plus"></i></a>

                                    </td>

                                </tr>
                                <?php endforeach;?>

                                </tbody>
                            </table>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-default js_addResponseFieldBtn" data-title="添加响应参数"  data-id="{{$api.id}}-0-0"><i class="fa fa-fw fa-plus"></i>添加参数</button>
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
        
        
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
        <input type="hidden" name="parent_id" value="<?=$model->parent_id?>">
        <input type="hidden" name="project_id" value="<?=$model->project_id?>">
    </form>

<?php
$this->extend('../layouts/footer');
?>