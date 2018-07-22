<?php
namespace Module\Shop\Service\Admin;

use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\URL;

class HomeController extends Controller {

    public function indexAction() {
        $menu_list = $this->getMenus();
        $user = Auth::user();
        return $this->show(compact('user', 'menu_list'));
    }

    public function dashboardAction() {
        return $this->show();
    }



    protected function getMenus() {
        return [
            [
                'name' => '常用菜单',
                'children' => [
                    [
                        'name' => '面板首页',
                        'url' => $this->getUrl('dashboard')
                    ],
                    [
                        'name' => '商品管理',
                        'children' => [
                            [
                                'name' => '商品列表',
                                'url' => $this->getUrl('goods')
                            ],
                            [
                                'name' => '商品分类',
                                'url' => $this->getUrl('category')
                            ],
                            [
                                'name' => '品牌列表',
                                'url' => $this->getUrl('brand')
                            ],
                            [
                                'name' => '选项类型',
                                'url' => $this->getUrl('type')
                            ],
                            [
                                'name' => '商品评价',
                                'url' => $this->getUrl('comment')
                            ],
                        ],
                    ],
                    [
                        'name' => '订单管理',
                        'children' => [
                            [
                                'name' => '订单列表',
                                'url' => $this->getUrl('order')
                            ],
                            [
                                'name' => '发货列表',
                                'url' => $this->getUrl('order/shipping')
                            ],
                            [
                                'name' => '订单日志',
                                'url' => $this->getUrl('order/log')
                            ],
                        ]
                    ],
                    [
                        'name' => '用户管理',
                        'children' => [
                            [
                                'name' => '用户列表',
                                'url' => $this->getUrl('user')
                            ],
                            [
                                'name' => '用户组',
                                'url' => $this->getUrl('user/group')
                            ],
                            [
                                'name' => '账户日志',
                                'url' => $this->getUrl('user/log')
                            ],
                        ]
                    ],
                    [
                        'name' => '运营管理',
                        'children' => [
                            [
                                'name' => '咨询反馈',
                                'url' => $this->getUrl('feedback')
                            ],
                            [
                                'name' => '售后服务',
                                'url' => $this->getUrl('aftersales')
                            ],
                            [
                                'name' => '订单统计',
                                'url' => $this->getUrl('stats/order')
                            ],
                            [
                                'name' => '营收统计',
                                'url' => $this->getUrl('stats/revenue')
                            ],
                            [
                                'name' => '访问统计',
                                'url' => $this->getUrl('stats/visitor')
                            ],
                            [
                                'name' => '友情链接',
                                'url' => $this->getUrl('friendlink')
                            ],
                        ]
                    ],
                    [
                        'name' => '广告管理',
                        'children' => [
                            [
                                'name' => '广告位列表',
                                'url' => $this->getUrl('ad/position')
                            ],
                            [
                                'name' => '广告列表',
                                'url' => $this->getUrl('ad')
                            ],
                        ]
                    ],
                    [
                        'name' => '文章管理',
                        'children' => [
                            [
                                'name' => '资讯列表',
                                'url' => $this->getUrl('article')
                            ],
                            [
                                'name' => '资讯分类',
                                'url' => $this->getUrl('article/category')
                            ],
                        ]
                    ],
                    [
                        'name' => '邮件管理',
                        'children' => [
                            [
                                'name' => '订阅列表',
                                'url' => $this->getUrl('mail/subscription')
                            ],
                            [
                                'name' => '邮件模板',
                                'url' => $this->getUrl('mail/template')
                            ],
                            [
                                'name' => '邮件队列',
                                'url' => $this->getUrl('mail/queue')
                            ],
                        ]
                    ]
                ]
            ],
            [
                'name' => '系统核心',
                'children' => [
                    [
                        'name' => '权限管理',
                        'children' => [
                            [
                                'name' => '管理员列表',
                                'url' => $this->getUrl('admin')
                            ],
                            [
                                'name' => '角色列表',
                                'url' => $this->getUrl('role')
                            ],
                        ]
                    ],
                    [
                        'name' => '系统配置',
                        'children' => [
                            [
                                'name' => '系统设置',
                                'url' => $this->getUrl('setting')
                            ],
                            [
                                'name' => '导航设置',
                                'url' => $this->getUrl('nav')
                            ],
                            [
                                'name' => '配送方式',
                                'url' => $this->getUrl('shipping')
                            ],
                            [
                                'name' => '支付方式',
                                'url' => $this->getUrl('payment')
                            ],
                            [
                                'name' => '物流承运商',
                                'url' => $this->getUrl('carrier')
                            ],
                        ]
                    ],
                    [
                        'name' => '系统应用',
                        'children' => [
                            [
                                'name' => '授权登录',
                                'url' => $this->getUrl('oauth')
                            ],
                        ]
                    ],
                    [
                        'name' => '系统工具',
                        'children' => [
                            [
                                'name' => '文件管理',
                                'url' => $this->getUrl('file')
                            ],
                            [
                                'name' => '数据库',
                                'url' => $this->getUrl('database')
                            ],
                            [
                                'name' => '系统清理',
                                'url' => $this->getUrl('cache')
                            ],
                        ]
                    ],
                ]
            ]
        ];

    }
}