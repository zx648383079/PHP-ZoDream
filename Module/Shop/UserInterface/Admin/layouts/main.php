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
        '@shop_admin.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        'ueditor/ueditor.config.js',
        'ueditor/ueditor.all.js',
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
        'fa fa-briefcase',
        [
            [
                '优惠券',
                './admin/order',
                'fa fa-list'
            ],
            [
                '满减/满送',
                './admin/order',
                'fa fa-list'
            ],
            [
                '拼团',
                './admin/order',
                'fa fa-list'
            ],
            [
                '限时秒杀',
                './admin/order',
                'fa fa-list'
            ],
            [
                '刮刮卡',
                './admin/order',
                'fa fa-list'
            ],
        ]
    ],
    [
        '订单管理',
        false,
        'fa fa-briefcase',
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
        'fa fa-book',
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
        'fa fa-briefcase',
        [
            [
                '基本设置',
                './admin/setting',
                'fa fa-gear'
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
], 'ZoDream Shop Admin') ?>
