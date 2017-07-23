<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
use Zodream\Domain\View\View;
/** @var $this View */
$js = <<<JS

JS;

$this->extend('layout/header')
    ->registerJs($js, View::JQUERY_READY);
?>
       <div class="page-header fixed">
            <ul class="path">
                <li>
                    <a>首页</a>
                </li>
                <li>
                    列表
                </li>
                <li class="active">
                    添加
                </li>
            </ul>
            <div class="title">
                 添加
            </div>
       </div>

       <form class="form-table" style="margin-top: 50px;">
           <div class="zd-tab">
                <ul class="zd-tab-head">
                    <li class="zd-tab-item active">
                        基本信息
                    </li><li class="zd-tab-item">
                        高级信息
                    </li>
                </ul>
                <div class="zd-tab-body">
                    <div class="zd-tab-item active">
                        <div class="input-group">
                            <label>姓名</label>
                            <div>
                                <input type="text">
                            </div>
                        </div>
                            <div class="input-group">
                                <label>姓名</label>
                                <div>
                                    <input type="text">
                                    <span class="input-desc">ggggggggggggggg</span>
                                </div>
                            </div>
                            <div class="input-group">
                                <label>姓名</label>
                                <div>
                                    <select>
                                        <option>选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-group">
                                <label>姓名</label>
                                <div>
                                    <label class="inline">
                                        <input type="checkbox"> 男
                                    </label>
                                    <label class="inline">
                                        <input type="checkbox"> 男
                                    </label>
                                </div>
                            </div>
                            <div class="input-group">
                                <label>姓名</label>
                                <div>
                                    <label class="inline">
                                        <input type="radio"> 男
                                    </label>
                                    <label class="inline">
                                        <input type="radio"> 男
                                    </label>
                                </div>
                            </div>
                            <div class="input-group">
                                <label>姓名</label>
                                <div>
                                    <textarea ></textarea>
                                </div>
                            </div>
                    </div>
                    <div class="zd-tab-item">
                        <textarea style="width: 100%;height: 200px;"></textarea>
                    </div>
                </div>
           </div>
           <div class="actions">
               <button class="btn">保存</button>
               <button class="btn" type="reset">重置</button>
           </div>
       </form>

<?php $this->extend('layout/footer'); ?>