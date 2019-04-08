<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;

/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@dialog.css',
        '@datetimer.css',
        '@shop_admin.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@jquery.datetimer.min.js',
        // 'ueditor/ueditor.config.js',
        // 'ueditor/ueditor.all.js',
        '@template-web.js',
        '@main.min.js',
        '@shop_admin.min.js'
    ]);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './admin',
        'fa fa-home',
    ],
    [
        '商品管理',
        false,
        'fa fa-briefcase',
        [
            [
                '商品列表',
                './admin/goods',
                'fa fa-list'
            ],
            [
                '新建商品',
                './admin/goods/create',
                'fa fa-plus'
            ],
            [
                '分类列表',
                './admin/category',
                'fa fa-list'
            ],
            [
                '品牌列表',
                './admin/brand',
                'fa fa-list'
            ],
            [
                '商品类型',
                './admin/attribute/group',
                'fa fa-list'
            ],
        ],
        true
    ],
    [
        '营销管理',
        false,
        'fa fa-bullhorn',
        [
            [
                '营销中心',
                './admin/activity/home',
                'fa fa-list'
            ],
            [
                '优惠券',
                './admin/activity/coupon',
                'fa fa-list'
            ],
            [
                '组合',
                './admin/activity/mix',
                'fa fa-list'
            ],
            [
                '返现',
                './admin/activity/cash_back',
                'fa fa-list'
            ],
            [
                '满减/满送',
                './admin/activity/discount',
                'fa fa-list'
            ],
            [
                '团购',
                './admin/activity/group_buy',
                'fa fa-list'
            ],
            [
                '拍卖',
                './admin/activity/auction',
                'fa fa-list'
            ],
            [
                '砍价',
                './admin/activity/bargain',
                'fa fa-list'
            ],
            [
                '限时秒杀',
                './admin/activity/seckill',
                'fa fa-list'
            ],
            [
                '抽奖',
                './admin/activity/lottery',
                'fa fa-list'
            ],
            [
                '试用',
                './admin/activity/free_trial',
                'fa fa-list'
            ],
        ]
    ],
    [
        '订单管理',
        false,
        'fa fa-cubes',
        [
            [
                '订单列表',
                './admin/order',
                'fa fa-list'
            ],
            [
                '新建订单',
                './admin/order/create',
                'fa fa-plus'
            ],
        ],
    ],
    [
        '文章管理',
        false,
        'fa fa-book',
        [
            [
                '文章列表',
                './admin/article',
                'fa fa-list'
            ],
            [
                '新建文章',
                './admin/article/create',
                'fa fa-plus'
            ],
            [
                '分类列表',
                './admin/article/category',
                'fa fa-list'
            ],
            [
                '新建分类',
                './admin/article/create_category',
                'fa fa-plus'
            ],
        ],
    ],
    [
        '广告管理',
        false,
        'fa fa-ad',
        [
            [
                '广告列表',
                './admin/ad',
                'fa fa-list'
            ],
            [
                '新建广告',
                './admin/ad/create',
                'fa fa-plus'
            ],
            [
                '广告位列表',
                './admin/ad/position',
                'fa fa-list'
            ],
            [
                '新建广告位',
                './admin/ad/create_position',
                'fa fa-plus'
            ],
        ],
    ],
    [
        '商城设置',
        false,
        'fa fa-cogs',
        [
            [
                '基本设置',
                './admin/setting',
                'fa fa-cog'
            ],
            [
                '支付列表',
                './admin/payment',
                'fa fa-list'
            ],
            [
                '新建支付',
                './admin/payment/create',
                'fa fa-plus'
            ],
            [
                '配送列表',
                './admin/shipping',
                'fa fa-list'
            ],
            [
                '新建配送',
                './admin/shipping/create',
                'fa fa-plus'
            ],
        ],
    ]
], $content, 'ZoDream Shop Admin') ?>
