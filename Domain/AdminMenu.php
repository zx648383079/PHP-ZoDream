<?php
namespace Domain;

use Zodream\Route\Router;

class AdminMenu {

    public static function all() {
        $menus = self::moduleMenu();
        array_unshift($menus, [
            '首页',
            '/',
            'fa fa-home',
        ] );
        $menus[] = [
            '系统管理',
            false,
            'fa fa-cogs',
            [
                [
                    '基本设置',
                    '/',
                    'fa fa-cog'
                ],
                [
                    '清除缓存',
                    '/cache',
                    'fa fa-trash'
                ],
                [
                    '生成SiteMap',
                    '/sitemap',
                    'fa fa-map'
                ]
            ],
            true
        ];
        return $menus;
    }

    public static function moduleMenu() {
        $menuList = [];
        $modules = config('modules');
        $oldGlobalModule = url()->getModulePath();
        $exist = [];
        foreach ($modules as $path => $module) {
            if (in_array($module, $exist)) {
                continue;
            }
            $exist[] = $module;
            url()->setModulePath($path);
            $module = Router::moduleInstance($module);
            if (!method_exists($module, 'adminMenu')) {
                continue;
            }
            $menuList = array_merge($menuList, $module->adminMenu());
        }
        url()->setModulePath($oldGlobalModule);
        return [
            [
                '友情链接',
                './friend_link',
                'fa fa-link'
            ],
            [
                '留言反馈',
                './feedback',
                'fa fa-cookie'
            ],
        ];
    }
}