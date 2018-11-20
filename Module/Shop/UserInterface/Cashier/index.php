<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>

<div class="cashier-page">
    <div class="container">
        <div class="panel">
            <div class="panel-header">
            收货信息
            </div>
            <div class="pannel-body">
                <div class="address-view">
                    <div>
                        <div>
                            <i class="fa fa-map"></i>
                            默认地址
                            <a href="" class="btn">修改</a>
                        </div>
                        <div>11111</div>
                        <div>11111</div>
                        <div>11111</div>
                    </div>
                    <div>
                        <a href="">地址切换</a>
                        <a href="" class="btn">新建地址</a>
                    </div>
                </div>
                <div class="address-edit">
                    <div>
                        <div>*所在地区:</div>
                        <div>
                            <select name="" id=""></select>
                            <select name="" id=""></select>
                            <select name="" id=""></select>
                        </div>
                        <div>*详细地址:</div>
                        <div>
                            <textarea name="" id="" cols="30" rows="10"></textarea>
                        </div>
                        <div>*收货人:</div>
                        <div>
                            <input type="text">
                        </div>
                        <div>*手机号码:</div>
                        <div>
                            <input type="text">
                        </div>
                    </div>
                    <div>
                        <div>
                            <div class="checkbox">
                                <input type="checkbox" name="is_default" value="1" id="checkboxInput" checked>
                                <label for="checkboxInput"></label>
                            </div>
                            设为默认
                        </div>
                        <button type="button" class="dialog-yes">确认</button><button type="button" class="dialog-close">取消</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
               <div>商品信息</div> 
               <div>单价</div>
               <div>数量</div>
               <div>小计</div>
               <div>实付</div>
            </div>
            <div class="panel-body">
                <div class="goods-item">
                    <div>商品信息</div> 
                    <div>单价</div>
                    <div>数量</div>
                    <div>小计</div>
                    <div>实付</div>
                </div>
            </div>
            <div class="panel-footer">
                <div>
                    <h4>发票信息：</h4>
                    <input type="checkbox" name="" id="">我要开发票
                </div>
                <div>
                    <h4>使用优惠券(0张)</h4>
                    
                </div>
                <div>
                    <div>商品合计:¥329.00</div>
                    <div>运费:¥0.00</div>
                </div>
                <div>
                    <h4>使用礼品卡</h4>
                    <input type="checkbox" name="" id="">可用余额
                </div>
                <div>
                   <div>应付总额:¥329.00</div>
                    <a href="" class="btn">去付款</a>
                    <div>王前186****1369</div>
                    <div>111111111111111</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dialog dialog-box" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">选择地址</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <div class="address-item active">
            <div>收货人: 111</div>
            <div>联系方式：186****1369</div>
            <div>收货地址：上海市上海市长宁区江苏路街道2369号405</div>
            <span>默认地址</span>
        </div>
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确定</button><button type="button" class="dialog-close">取消</button>
    </div>
</div>

<div class="dialog dialog-box" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">发票信息</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确定</button><button type="button" class="dialog-close">取消</button>
    </div>
</div>