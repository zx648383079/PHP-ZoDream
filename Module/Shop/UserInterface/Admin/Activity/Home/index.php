<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '营销中心';
?>
<div class="panel">
    <div class="header">
        平台活动
    </div>
    <div class="body">
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>批发管理</h3>
                <p>添加批发管理</p>
            </div>
        </div>
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>专题管理</h3>
                <p>通过可视化进行装修专题</p>
            </div>
        </div>
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>优惠活动</h3>
                <p>设置优惠活动时间</p>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="header">
        交易玩法
    </div>
    <div class="body">
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>拍卖</h3>
                <p>设置拍卖活动</p>
            </div>
        </div>
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>夺宝奇兵</h3>
                <p>夺宝奇兵活动设置</p>
            </div>
        </div>
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>团购</h3>
                <p>设置团购活动</p>
            </div>
        </div>
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>秒杀</h3>
                <p>设置拍卖活动</p>
            </div>
        </div>
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>积分商城</h3>
                <p>夺宝奇兵活动设置</p>
            </div>
        </div>
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>预售</h3>
                <p>设置团购活动</p>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="header">
        红包卡券
    </div>
    <div class="body">
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>红包</h3>
                <p>添加发放红包类型</p>
            </div>
        </div>
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>超值礼包</h3>
                <p>通过可视化进行装修专题</p>
            </div>
        </div>
        <a class="column-item" href="<?=$this->url('./admin/activity/coupon')?>">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>优惠券</h3>
                <p>优惠券的领取和发放</p>
            </div>
        </a>
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>储值卡</h3>
                <p>通过可视化进行装修专题</p>
            </div>
        </div>
        <div class="column-item">
            <div class="icon">
                <div class="fa fa-folder"></div>
            </div>
            <div class="content">
                <h3>礼品卡</h3>
                <p>设置优惠活动时间</p>
            </div>
        </div>
    </div>
</div>