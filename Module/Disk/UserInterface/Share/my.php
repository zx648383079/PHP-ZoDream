<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '我的分享';

$base_url = $this->url('./');
$js = <<<JS
    require_my_share('{$base_url}');
JS;
$this->registerJs($js)->extend('layouts/header');
?>

<div id="content">
    <div class="table-header">
        <div v-show="checkCount < 1" class="row">
            <div class="col-md-1">
                <span class="checkbox" v-on:click="checkAll" v-bind:class="{'checked': isAllChecked}"></span>
            </div>
            <div class="col-md-3"  v-on:click="setOrder('name')">
                <span>文件名</span>
                <span v-show="orderKey == 'name' && order > 0" class="fa fa-long-arrow-up"></span>
                <span v-show="orderKey == 'name' && order < 0" class="fa fa-long-arrow-down"></span>
            </div>
            <div class="col-md-2" v-on:click="setOrder('create_at')">
                <span>分享时间</span>
                <span v-show="orderKey == 'create_at' && order > 0" class="fa fa-long-arrow-up"></span>
                <span v-show="orderKey == 'create_at' && order < 0" class="fa fa-long-arrow-down"></span>
            </div>
            <div class="col-md-2" v-on:click="setOrder('view_count')">
                <span>浏览次数</span>
                <span v-show="orderKey == 'view_count' && order > 0" class="fa fa-long-arrow-up"></span>
                <span v-show="orderKey == 'view_count' && order < 0" class="fa fa-long-arrow-down"></span>
            </div>
            <div class="col-md-2" v-on:click="setOrder('save_count')">
                <span>保存次数</span>
                <span v-show="orderKey == 'save_count' && order > 0" class="fa fa-long-arrow-up"></span>
                <span v-show="orderKey == 'save_count' && order < 0" class="fa fa-long-arrow-down"></span>
            </div>
            <div class="col-md-2" v-on:click="setOrder('down_count')">
                <span>下载次数</span>
                <span v-show="orderKey == 'down_count' && order > 0" class="fa fa-long-arrow-up"></span>
                <span v-show="orderKey == 'down_count' && order < 0" class="fa fa-long-arrow-down"></span>
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
                <button v-on:click="deleteItem(0)" class="btn btn-default">
                    <i class="fa fa-chain-broken" aria-hidden="true"></i>
                    取消分享
                </button>
            </div>
        </div>
    </div> <!-- END HEADER -->
    <div class="table-body">
        <div class="zd_list">
            <div v-for="item in sortFiles " v-on:click="check(item)" class="row">
                <div class="col-md-1">
                    <span class="checkbox" v-bind:class="{'checked': item.checked}"></span>
                </div>
                <div v-on:click.stop="enter(item)" class="col-md-3">
                    <i class="fa" v-bind:class="{'fa-lock': item.mode == 'protected', 'fa-user': item.mode == 'private', 'fa-unlock': item.mode == 'public', 'fa-users': item.mode == 'internal'}" aria-hidden="true"></i>
                    <a target="_blank" v-bind:href="'<?=$this->url(['./share'])?>&id=' + item.id">{{item.name}}</a>
                </div>
                <div class="col-md-2 share_time">
                    <span class="hover-hide">{{item.created_at | time}}</span>
                    <div class="row-tools">
                        <i v-on:click.stop="deleteItem(item)" class="fa fa-chain-broken" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-md-2">
                    <span>{{item.view_count}}</span>
                </div>
                <div class="col-md-2">
                    <span>{{item.save_count}}</span>
                </div>
                <div class="col-md-2">
                    <span>{{item.down_count}}</span>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
$this->extend('layouts/footer');
?>