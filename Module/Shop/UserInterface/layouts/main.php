<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@zodream.css',
    '@dialog.css',
    '@shop.css'
])->registerJsFile([
    '@jquery.min.js',
    '@jquery.dialog.min.js',
    '@main.min.js',
    '@shop.min.js'
]);
?>
<!DOCTYPE html>
<html lang="<?=$this->get('language', 'zh-CN')?>">
   <head>
       <meta name="viewport" content="width=device-width, initial-scale=1"/>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <meta name="Description" content="<?=$this->description?>" />
       <meta name="keywords" content="<?=$this->keywords?>" />
       <title><?=$this->title?></title>
       <?=$this->header();?>
   </head>
   <body>
   <header>
        <div class="header-top">
            <div class="container">
                <div class="top-left">
                    <a href="">
                        好的生活，没那么贵
                    </a>
                    <div class="top-notice">
                        <i class="fa fa-volume-up"></i>
                        <ul class="notice-list">
                            <li>
                                <a href="">双十一期间严选客服服务时间通知</a>
                            </li>
                            <li>
                                <a href="">双十一期间严选发货时效说明</a>
                            </li>
                        </ul>
                    </div>
                    
                </div>
                <div class="top-right">
                    <div class="top-item">
                        <a href="">登录</a>
                        <a href="">注册</a>
                    </div>
                    <div class="top-item">
                        我的订单
                    </div>
                    <div class="top-item">
                        会员
                    </div>
                    <div class="top-item">
                        甄选家
                    </div>
                    <div class="top-item">
                        企业采购
                    </div>
                    <div class="top-item">
                        客户服务
                    </div>
                    <div class="top-item">
                        <i class="fa fa-mobile"></i>
                        APP
                    </div>
                </div>
            </div>
        </div>
        <div class="header-main">
            <div class="container">
                <div class="header-logo">
                    <img src="http://yanxuan.nosdn.127.net/3db3a7a0bae656df51581fa14f4061d9.gif" alt="">
                </div>
                <ul class="header-nav">
                    <li>
                        <a href="">首页</a>
                    </li>
                    <li>
                        <a href="">居家</a>
                        <div class="nav-dropdown">
                            <div class="nav-column">
                                <div class="nav-title">床品</div>
                                <div class="nav-item">
                                    <img src="https://yanxuan.nosdn.127.net/785a1507ce654746875063805c6c4235.png" alt="">
                                    <span>床品件套</span>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
   </header>
   <?=$content?>
   <footer>
        <div class="footer-top">
            <div class="container">
                <div class="top-item">
                    <h4>客户服务</h4>
                    <a>
                        <i class="fa fa-headphones"></i>
                        <p>在线客服</p>
                    </a>
                    <a>
                        <i class="fa fa-commenting-o" aria-hidden="true"></i>
                        <p>用户反馈</p>
                    </a>
                </div>
                <div class="top-item">
                    <h4>何为严选</h4>
                    <p>
                        网易原创生活类电商品牌，秉承网易一贯的严谨态度，我们深入世界各地，从源头全程严格把控商品生产环节，力求帮消费者甄选到优质的商品
                    </p>
                </div>
                <div class="top-item">
                    <h4>扫码关注公众号</h4>
                    <img src="/assets/images/wx.jpg" alt="自在test" width="104" height="104">
                </div>
            </div>
        </div>
        <div class="footer-main">
            <div class="container">
                <div class="top-grid">
                    <div class="top-item">
                        <i class="fa fa-recycle"></i>
                        <span>30天无忧退换货</span>
                    </div>
                    <div class="top-item">
                        <i class="fa fa-rocket"></i>
                        <span>满88免邮费</span>
                    </div>
                    <div class="top-item">
                        <i class="fa fa-shield"></i>
                        <span>永久品质保证</span>
                    </div>
                </div>
                <div class="footer-hr"></div>
                <div class="footer-info">
                    <div class="nav-list">
                        <a>关于我们</a>
                        <b>|</b>
                        <a>帮助中心</a>
                        <b>|</b>
                        <a>售后服务</a>
                        <b>|</b>
                        <a>配送与验收</a>
                        <b>|</b>
                        <a>商务合作</a>
                        <b>|</b>
                        <a >企业采购</a>
                        <b>|</b>
                        <a>开放平台</a>
                        <b>|</b>
                        <a>搜索推荐</a>
                        <b>|</b>
                        <a>友情链接</a>
                    </div>
                    <div class="copyright">
                            版权所有 © 2018-2019
                    </div>
                </div>
            </div>
        </div>
   </footer>
   <?=$this->footer()?>
   </body>
</html>