<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $model->id ?  '编辑接口:'.$model->name : '新建接口';
$js = <<<JS
$('[name=parent_id]').change(function () { 
    $(".extent-box").toggle($(this).val() > 0);
});
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
            <label>上级</label>
            <select name="parent_id">
                <option value="0">顶级</option>
                <?php foreach($tree_list as $item):?>
                <?php if($item['id'] != $model->id):?>
                <option value="<?=$item['id']?>" <?= $item['id'] == $model->parent_id ? 'selected' : '' ?>><?=$item['name']?></option>
                <?php endif;?>
                <?php endforeach;?>
            </select>
        </div>
        
        <div class="extent-box" <?=$model->parent_id < 1 ? ' style="display:none"' : ''?>>
        <div class="input-group">
            <label>请求类型</label>
            <select name="method">
            <?php foreach($model->method_list as $item):?>
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
                            <table id="table-3" class="table table-striped table-bordered table-hover">
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
                                        <a href="javascript:;" onclick="editField(this, '<?=$this->url('./admin/api/edit_field', ['id' => $item['id']])?>');" class="fa fa-pencil"></a>
                                        <a href="javascript:;" onclick="delField(this, '<?=$this->url('./admin/api/delete_field', ['id' => $item['id']])?>');" class="fa fa-trash-o"></a>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                            </div>

                            <div class="form-group">
                                <a href="javascript:addField('<?=$this->url('./admin/api/create_field', ['kind' => 3, 'api_id' => $model->id])?>');" class="btn btn-default"><i class="fa fa-fw fa-plus"></i>添加参数</a>
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
                                <?php foreach($request_fields as $item):?>
                                <tr>
                                    <td><?=$item['name']?></td>
                                    <td><?=$item['title']?></td>
                                    <td><?=$item->type_list[$item->type]?></td>
                                    <td><?=$item['is_required'] ? '是' : '否'?></td>
                                    <td><?=$item['default_value']?></td>
                                    <td><?=$item['remark']?></td>
                                    <td style="width: 10%">
                                    <a href="javascript:;" onclick="editField(this, '<?=$this->url('./admin/api/edit_field', ['id' => $item['id']])?>');" class="fa fa-pencil"></a>
                                        <a href="javascript:;" onclick="delField(this, '<?=$this->url('./admin/api/delete_field', ['id' => $item['id']])?>');" class="fa fa-trash-o"></a>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                            </div>

                            <div class="form-group">
                            <a href="javascript:addField('<?=$this->url('./admin/api/create_field', ['kind' => 1, 'api_id' => $model->id])?>');" class="btn btn-default"><i class="fa fa-fw fa-plus"></i>添加参数</a>
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
                                <?php foreach($response_fields as $item):?>
                                <tr class="<?=$item['parent_id'] < 1 ? 'warning' : ''?>">

                                    <td style="text-align: left;padding-left: 50px;"><?=$item['parent_id'] ? '└' : ''?><?=$item['name']?></td>
                                    <td><?=$item['title']?></td>
                                    <td><?=$item->type_list[$item->type]?></td>

                                    <td><?=$item['mock']?></td>

                                    <td><?=$item['remark']?></td>

                                    <td style="width: 10%">

                                        <a href="javascript:;" onclick="editField(this, '<?=$this->url('./admin/api/edit_field', ['id' => $item['id']])?>');" class="fa fa-pencil"></a>
                                        <a href="javascript:;" onclick="delField(this, '<?=$this->url('./admin/api/delete_field', ['id' => $item['id']])?>');" class="fa fa-trash-o"></a>
                                        <?php if(in_array($item->type, ['array', 'object'])):?>
                                        <a href="javascript:;" onclick="addField('<?=$this->url('./admin/api/create_field', ['kind' => 2, 'parent_id' => $item['id'], 'api_id' => $model->id])?>', this);" class="btn btn-xs"><i class="fa fa-fw fa-plus"></i></a>
                                        <?php endif;?>
                                        

                                    </td>

                                </tr>
                                <?php endforeach;?>

                                </tbody>
                            </table>
                            </div>

                            <div class="form-group">
                            <a href="javascript:addField('<?=$this->url('./admin/api/create_field', ['kind' => 2, 'api_id' => $model->id])?>');" class="btn btn-default"><i class="fa fa-fw fa-plus"></i>添加参数</a>
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
        <input type="hidden" name="id" value="<?=$model->id?>">
        <input type="hidden" name="parent_id" value="<?=$model->parent_id?>">
        <input type="hidden" name="project_id" value="<?=$model->project_id?>">
    </form>

<?php
$this->extend('../layouts/footer');
?>