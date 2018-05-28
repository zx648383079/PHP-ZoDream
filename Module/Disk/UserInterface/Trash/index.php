<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '回收站';

$base_url = $this->url('./');

$js = <<<JS
require_trash('{$base_url}');
JS;
$this->registerJs($js);
?>

<div id="content">
    <div class="page-header">
        <div class="actions">
            <button v-on:click="clear"><i class="fa fa-trash" aria-hidden="true"></i>清空回收站</button>
        </div>
    </div>
    <div class="table-header">
        <div v-show="checkCount < 1" class="row">
            <div class="col-md-1">
                <span class="checkbox" v-on:click="checkAll" v-bind:class="{'checked': isAllChecked}"></span>
            </div>
            <div class="col-md-6"  v-on:click="setOrder('name')">
                <span>文件名</span>
                <span v-show="orderKey == 'name' && order > 0" class="fa fa-long-arrow-up"></span>
                <span v-show="orderKey == 'name' && order < 0" class="fa fa-long-arrow-down"></span>
            </div>
            <div class="col-md-2" v-on:click="setOrder('size')">
                <span>大小</span>
                <span v-show="orderKey == 'size' && order > 0" class="fa fa-long-arrow-up"></span>
                <span v-show="orderKey == 'size' && order < 0" class="fa fa-long-arrow-down"></span>
            </div>
            <div class="col-md-3" v-on:click="setOrder('delete_at')">
                <span>删除时间</span>
                <span v-show="orderKey == 'delete_at' && order > 0" class="fa fa-long-arrow-up"></span>
                <span v-show="orderKey == 'delete_at' && order < 0" class="fa fa-long-arrow-down"></span>
            </div>
        </div>
        <div v-show="checkCount > 0" class="row">
            <div class="col-md-1">
                <span class="checkbox" v-on:click="checkAll" v-bind:class="{'checked': isAllChecked}"></span>
            </div>
            <div class="col-md-3" style="font-size: 16px">
                已选中 {{ checkCount }} 个文件/文件夹
            </div>
            <div class="col-md-8">
                <button v-on:click="reset(0)" class="btn btn-default">
                    <i class="fa fa-reply" aria-hidden="true"></i>
                    还原
                </button>
                <button v-on:click="deleteItem(0)" class="btn btn-default">
                    <span class="fa fa-trash"></span>
                    删除
                </button>
            </div>
        </div>
    </div> <!-- END HEADER -->
    <div class="table-body">
        <div class="zd_list">
            <div v-for="item in sortFiles" v-on:click="check(item)" class="row">
                <div class="col-md-1">
                    <span class="checkbox" v-bind:class="{'checked': item.checked}"></span>
                </div>
                <div v-on:click.stop="enter(item)" class="col-md-6">
                    <span class="fa" v-bind:class="{'fa-folder': item.file_id < 1, 'fa-file': item.file_id > 0}"></span>
                    <span>{{item.name}}</span>
                </div>
                <div class="col-md-2">
                    <span>{{item.size | size}}</span>
                </div>
                <div class="col-md-3">
                    <span class="hover-hide">{{item.deleted_at | time}}</span>
                    <div class="row-tools">
                        <i v-on:click.stop="reset(item)" class="fa fa-reply" aria-hidden="true"></i>
                        <i v-on:click.stop="deleteItem(item)" class="fa fa-trash" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>