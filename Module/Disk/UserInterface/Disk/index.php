<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '全部文件';

$base_url = $this->url('./');
$md5_url = $this->asset('@disk.md5.min.js');

$js = <<<JS
    require_disk('{$base_url}', '{$md5_url}');
JS;
$this->registerJs($js)
    ->registerCssFile('@APlayer.min.css')
    ->registerJsFile('@APlayer.min.js');
?>

<div id="content" style="display:none;">
    <div class="row page-header">
        <div class="col-md-4 zd_new">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button">
                    <span class="fa fa-upload"></span>
                    上传
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#" class="uploadFile">上传文件</a></li>
                    <li><a href="#" class="uploadFolder">上传文件夹</a></li>
                </ul>
            </div>
            <button class="btn btn-default" data-type="create">
                <span class="fa fa-plus"></span>新建文件夹</button>
        </div>
        <div class="actions">
            <button class="btn" v-on:click="refresh"><span class="fa fa-sync"></span></button>
            <button class="btn" v-bind:class="{'active': isList}" v-on:click="setList(true)"><span class="fa fa-th-list"></span></button>
            <button class="btn" v-bind:class="{'active': !isList}" v-on:click="setList(false)"><span class="fa fa-th-large"></span></button>
        </div>
    </div>
    <div class="row crumb-box">
        <div class="col-xs-2" v-show="crumb.length > 1">
            <a href="#" v-on:click="top">返回上一级</a> |
        </div>
        <div class="col-xs-10">
            <ol class="breadcrumb">
                <li v-for="(item, index) in crumb" v-bind:class="{'active': index == crumb.length - 1}">
                    <a href="#" v-on:click="level(item)" v-show="index < crumb.length - 1">{{item.name}}</a>
                    <span v-show="index == crumb.length - 1">{{item.name}}</span>
                </li>
            </ol>
        </div>
    </div>
    <div class="table-header">
        <div v-show="isList && checkCount < 1" class="file-row">
            <div>
                <span class="checkbox" v-on:click="checkAll" v-bind:class="{'checked': isAllChecked}"></span>
            </div>
            <div v-on:click="setOrder('name')">
                <span>文件名</span>
                <span v-show="orderKey == 'name' && order > 0" class="fa fa-long-arrow-up"></span>
                <span v-show="orderKey == 'name' && order < 0" class="fa fa-long-arrow-down"></span>
            </div>
            <div v-on:click="setOrder('size')">
                <span>大小</span>
                <span v-show="orderKey == 'size' && order > 0" class="fa fa-long-arrow-up"></span>
                <span v-show="orderKey == 'size' && order < 0" class="fa fa-long-arrow-down"></span>
            </div>
            <div v-on:click="setOrder('update_at')">
                <span>修改时间</span>
                <span v-show="orderKey == 'update_at' && order > 0" class="fa fa-long-arrow-up"></span>
                <span v-show="orderKey == 'update_at' && order < 0" class="fa fa-long-arrow-down"></span>
            </div>
        </div>
        <div v-show="!isList && checkCount < 1" class="file-edit-row">
            <div>
                <span class="checkbox" v-on:click="checkAll" v-bind:class="{'checked': isAllChecked}"></span>
            </div>
        </div>
        <div v-show="checkCount > 0" class="file-edit-row">
            <div>
                <span class="checkbox" v-on:click="checkAll" v-bind:class="{'checked': isAllChecked}"></span>
            </div>
            <div style="font-size: 16px">
                已选中 {{ checkCount }} 个文件/文件夹
            </div>
            <div>
                <button v-on:click="shareAll" class="btn btn-default">
                    <i class="fa fa-share"></i>
                    分享
                </button>
                <button v-on:click="downloadAll" class="btn btn-default">
                    <i class="fa fa-download"></i>
                    下载
                </button>
                <button v-on:click="deleteAll" class="btn btn-default">
                    <i class="fa fa-trash"></i>
                    删除
                </button>
                <button v-on:click="moveAll" class="btn btn-default">
                    <i class="fa fa-copy"></i>
                    复制到
                </button>
                <button v-on:click="copyAll" class="btn btn-default">
                    <i class="fa fa-arrows-alt"></i>
                    移动到
                </button>
                <button v-on:click="rename" class="btn btn-default">
                    <i class="fa fa-edit"></i>
                    重命名
                </button>
            </div>
        </div>
    </div> <!-- END HEADER -->
    <div class="table-body">
        <div v-show="isList" class="zd_list">
            <div v-for="item in sortFiles " v-on:click="check(item)" class="file-row">
                <div>
                    <span class="checkbox" v-bind:class="{'checked': item.checked}"></span>
                </div>
                <div v-on:click.stop="enter(item)" v-bind:class="{'row-editable': item.is_edit}">
                    <span class="fa" v-bind:class="{'fa-folder': item.file_id < 1, 'fa-file': item.file_id > 0}"></span>
                    <span class="row-name">{{item.name}}</span>
                    <div class="row-edit">
                        <input type="text" v-model="item.new_name">
                        <i class="fa fa-check"  v-on:click.stop="saveEdit(item)"></i>
                        <i class="fa fa-close" v-on:click.stop="closeEdit(item)"></i>
                    </div>
                </div>
                <div>
                    <span v-if="item.file">{{item.file.size | size}}</span>
                </div>
                <div>
                    <span class="hover-hide">{{item.updated_at | time}}</span>
                    <div class="row-tools">
                        <span v-on:click.stop="share(item)" class="fa fa-share"></span>
                        <span v-on:click.stop="download(item)" class="fa fa-download"></span>
                        <span v-on:click.stop="move(item)" class="fa fa-arrows-alt"></span>
                        <span v-on:click.stop="copy(item)" class="fa fa-copy"></span>
                        <span v-on:click.stop="rename(item)" class="fa fa-edit"></span>
                        <span v-on:click.stop="deleteItem(item)" class="fa fa-trash"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-show="!isList" class="grid-box">
        <div class="row">
            <div v-for="item in files" v-on:click="check(item)" class="col-md-2">
                <div class="file-icon">
                    <i class="checkbox" v-bind:class="{'checked': item.checked}"></i>
                    <i class="fa" v-bind:class="{'fa-folder': item.file_id < 1, 'fa-file': item.file_id > 0}"></i>
                </div>
                <div class="row-name">
                    <a href="#" v-on:click.stop="enter(item)">{{item.name}}</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="upload" class="dialog dialog-box upload-dialog" v-bind:class="{'min': mode == 1, 'max': mode == 2}">
    <div class="dialog-header">
        <span class="dialog-title">{{title}}</span>
        <div class="dialog-actions">
            <span v-show="mode != 2" v-on:click="mode = 2" class="fa fa-resize-full"></span>
            <span v-show="mode != 1" v-on:click="mode = 1" class="fa fa-resize-small"></span>
            <span v-on:click="mode = 0" class="fa fa-remove dialog-close"></span>
        </div>
    </div>
    <div class="dialog-body">
        <div class="row">
            <div class="col-md-4">
                文件(夹)名
            </div>
            <div class="col-md-2">
                大小
            </div>
            <div class="col-md-2">
                上传目录
            </div>
            <div class="col-md-2">
                状态
            </div>
            <div class="col-md-2">
                操作
            </div>
        </div>
        <div class="file-box">
            <div v-for="(item, index) in files" class="row">
                <div class="col-md-4">
                    {{item.name}}
                </div>
                <div class="col-md-2">
                    {{item.size | size}}
                </div>
                <div class="col-md-2">
                    {{item.parent_name}}
                </div>
                <div class="col-md-3 status">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" v-bind:style="{width: item.process + '%'}">

                        </div>
                    </div>
                    <div class="zd_tag">
                        <span>{{item.status | status}}</span> <span v-show="item.status == 0 || item.status == 2">{{ item.process }}%</span>
                    </div>
                </div>
                <div class="col-md-1">
                    <span v-on:click.stop="deleteItem(index)" class="fa fa-trash"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dialog dialog-box" data-type="dialog" id="folderModal">
    <div class="dialog-header">
        <div class="dialog-title">选择文件夹</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <ul class="tree-box">
            <li class="active" data-id="0">
                <div class="tree-item">
                    <span></span>
                    <span></span>
                    <span>全部文件</span>
                </div>
            </li>
        </ul>
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确认</button>
        <button type="button" class="dialog-close">取消</button>
    </div>
</div>


<div class="dialog dialog-box" data-type="dialog" id="shareModal">
    <div class="dialog-header">
        <div class="dialog-title">分享文件</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body tab-box">
        <ul class="tab-header">
            <li class="tab-item" v-bind:class="{active: modeType < 1}" v-on:click="modeType = 0">链接分享</li>
            <li class="tab-item" v-bind:class="{active: modeType == 2}" v-on:click="modeType = 2">分享给好友</li>
        </ul>

        <div class="tab-body">
            <div class="tab-item" v-bind:class="{active: modeType < 1}">
                <div class="row" v-if="result">
                    <input type="text" class="form-control" readonly v-model="result">
                </div>
                <div class="row" v-if="!result">
                    <div class="col-md-4">
                        <button class="btn btn-primary" v-on:click="create(0)">创建公开链接</button>
                    </div>
                    <div class="col-md-8">
                        <p>（文件会出现在你的分享主页，其他人都能查看下载）</p>
                    </div>
                </div>
                <div class="row" v-if="!result">
                    <div class="col-md-4">
                        <button class="btn btn-primary" v-on:click="create(1)">创建私密链接</button>
                    </div>
                    <div class="col-md-8">
                        <p>（只有分享的好友能看到，其他人都看不到）</p>
                    </div>
                </div>
            </div>
            <div class="tab-item" v-bind:class="{active: modeType == 2}">
                <div class="row">
                    <div class="col-md-5">
                        <ul class="zd_listbox" id="users">
                            <li v-for="item in users" v-on:click="item.select = !item.select" v-bind:class="{'selected': item.select}">{{item.name}}</li>
                        </ul>
                    </div>
                    <div class="col-md-2" style="padding-top: 90px;">
                        <button v-on:click="select" class="btn btn-primary">&gt;&gt;&gt;</button>
                        <button v-on:click="remove" class="btn btn-primary">&lt;&lt;&lt;</button>
                    </div>
                    <div class="col-md-5">
                        <input class="form-control" v-on:keyup.enter="getUser" v-model="name" type="text" placeholder="请输入姓名回车键添加">
                        <ul class="zd_listbox" id="selectUsers" style="height: 190px">
                            <li v-for="item in selectUsers" v-on:click="item.select = !item.select" v-bind:class="{'selected': item.select}">{{item.name}}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dialog-footer">
        <button type="button" v-show="modeType == 2" class="btn btn-primary">分享给好友</button>
        <button type="button" class="dialog-close">关闭</button>
    </div>
</div>

<div id="player"></div>