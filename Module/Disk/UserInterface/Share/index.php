<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $model->name;
$base_url = $this->url('./');

$js = <<<JS
    require_share('{$base_url}', {$model->id});
JS;
$this->registerJs($js);
?>

<div class="share-page">
    <div id="content">
        <div class="row">
                <div class="col-md-8">
                    <h3><?=$model->name?></h3>
                    <p>分享时间：<?=$model->created_at?></p>
                </div>
                <div class="col-md-4 text-right">
                    <?php if (auth()->id() == $model->user_id):?>
                        <button v-on:click="cancel" class="btn btn-danger">
                            <i class="fa fa-chain-broken" aria-hidden="true"></i>
                            取消分享
                        </button>
                    <?php elseif (!auth()->guest()):?>
                        <button v-on:click="save" class="btn btn-danger">
                            <i class="glyphicon glyphicon-floppy-save" aria-hidden="true"></i>
                            保存至
                        </button>
                    <?php endif;?>
                    <button v-on:click="downloadAll" class="btn btn-primary">
                        <i class="fa fa-download" aria-hidden="true"></i>
                        下载
                    </button>
                </div>
            </div>
            <div class="row page-header">
                <div class="actions">
                    <button class="btn" v-on:click="refresh"><span class="fa fa-refresh"></span></button>
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
                <div v-show="isList && checkCount < 1" class="row">
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
                    <div class="col-md-3" v-on:click="setOrder('update_at')">
                        <span>修改时间</span>
                        <span v-show="orderKey == 'update_at' && order > 0" class="fa fa-long-arrow-up"></span>
                        <span v-show="orderKey == 'update_at' && order < 0" class="fa fa-long-arrow-down"></span>
                    </div>
                </div>
                <div v-show="!isList && checkCount < 1" class="row">
                    <div class="col-md-1">
                        <span class="checkbox" v-on:click="checkAll" v-bind:class="{'checked': isAllChecked}"></span>
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
                        <button v-on:click="downloadAll" class="btn btn-default">
                            <span class="fa fa-download"></span>
                            下载
                        </button>
                        <?php if (!auth()->guest() && auth()->id() != $model->user_id):?>
                            <button v-on:click="saveAll" class="btn btn-default">
                                <span class="fa fa-save"></span>
                                保存到
                            </button>
                        <?php endif;?>
                    </div>
                </div>
            </div> <!-- END HEADER -->
            <div class="table-body">
                <div v-show="isList" class="zd_list">
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
                            <span>{{item.update_at | time}}</span>
                            <div class="zd_tool">
                                <?php if (!auth()->guest() && auth()->id() != $model->user_id):?>
                                    <span v-on:click.stop="save(item)" class="fa fa-save"></span>
                                <?php endif;?>
                                <span v-on:click.stop="download(item)" class="fa fa-download"></span>
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
    <div>
        <div class="user-box">
            <div class="avatar">
                <img src="<?=$user->avatar?>">
            </div>
            <p class="text-center"><?=$user->name?></p>
        </div>
    </div>
</div>

<?php if(!auth()->guest() && auth()->id()):?>
<div class="dialog dialog-box" data-type="dialog" id="folderModal">
    <div class="dialog-header">
        <div class="dialog-title">选择文件夹</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <ul class="zd_tree">
            <li class="active" data-id="0">
                <div class="zd_tree_item">
                    <span></span>
                    <span></span>
                    <span>全部文件</span>
                </div>
            </li>
        </ul>
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确定</button>
        <button type="button" class="dialog-close">取消</button>
    </div>
</div>
<?php endif;?>