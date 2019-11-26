<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '接口调试';
?>

<div id="debug-box">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <h1>接口调试 </h1>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <form id="js_requestForm" method="post" action="<?=$this->url('./@admin/api/debug_result')?>">
            <input type="hidden" name="id" value="<?=$api->id?>">
        <div class="row">
            <div class="col-lg-7">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="input-group uri-box">
                                
                                <select id="uri-list">
                                <?php foreach($project->environment as $item):?>
                                <option value="<?=$item['name']?>" data-url="<?=$api->getUri($item['domain'])?>"><?=$item['name']?></option>
                                <?php endforeach;?>
                                </select>
                                <input type="text" class="form-control js_envUrl" name="url" placeholder="请输入请求地址" value="<?=$api->getUri($project->environments[0]['domain'])?>" />
                                <select name="method">
                                <?php foreach($api->method_list as $item):?>
                                <option value="<?=$item?>" <?= $item == $api->method ? 'selected' : '' ?>><?=$item?></option>
                                <?php endforeach;?>
                                </select>
                                <button class="btn btn-success js_submit" type="button"><i class="fa fa-search"></i> 请求</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Header参数 <a href="javascript:void(0);" class="fa fa-plus js_addHeaderBtn" title="添加参数"></a>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div class="table-responsive">
                                        <table class="table header-table">
                                            <tbody>
                                            <?php foreach($header_fields as $item):?>
                                            <tr>
                                                <td style="width:40%"> <input name="header[key][]" class="form-control" placeholder="key" value="<?=$item['name']?>" /> </td>
                                                <td style="width:55%"> <input name="header[value][]" class="form-control" placeholder="value" value="<?=$item['default_value']?>" /> </td>
                                                <td style="width:5%">
                                                    <a href="javascript:void(0);" class="fa fa-trash-o" data-title="删除header参数"></a> </td>
                                            </tr>
                                            <?php endforeach;?>
                                            </tbody>
                                        </table>
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
                                <a href="javascript:void(0);" class="fa fa-plus js_addRequestBtn" data-toggle="tooltip" data-placement="right" title="添加参数"></a>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div class="table-responsive">
                                        <table class="table request-table">
                                            <tbody>
                                            <?php foreach($request_fields as $item):?>
                                            <tr>
                                                <td style="width:40%"><input name="request[key][]" class="form-control" placeholder="key" datatype="*"  value="<?=$item['name']?>"/></td>
                                                <td style="width:55%"><input name="request[value][]" class="form-control" placeholder="value" datatype="*"  value="<?=$item['default_value']?>"/></td>
                                                <td style="width:5%">
                                                    <a href="javascript:void(0);" class="fa fa-trash-o"></a>
                                                </td>
                                            </tr>
                                            <?php endforeach;?>
                                            </tbody>
                                        </table>
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
            <div class="col-lg-5 js_responseBox" >
                <?php $this->extend('./debugResult');?>
            </div>
        </div>
        </form>
    </div>