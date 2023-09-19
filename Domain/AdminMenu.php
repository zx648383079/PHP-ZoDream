<?php
declare(strict_types=1);
namespace Domain;

use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\ModuleRoute;

class AdminMenu {

    public static function all() {
        $menus = self::moduleMenu();
        array_unshift($menus, [
            '首页',
            './',
            'fa fa-home',
        ] );
        $menus[] = [
            '系统管理',
            false,
            'fa fa-cogs',
            [
                [
                    '基本设置',
                    './',
                    'fa fa-cog'
                ],
                [
                    '清除缓存',
                    './cache',
                    'fa fa-trash'
                ],
                [
                    '生成SiteMap',
                    './sitemap',
                    'fa fa-map'
                ]
            ],
            true
        ];
        return $menus;
    }

    public static function moduleMenu() {
        $menuItems = [];
        $modules = config('route.modules');
        /** @var ModuleRoute $route */
        $route = app(ModuleRoute::class);
        $exist = [];
        foreach ($modules as $path => $module) {
            if (empty($module)) {
                continue;
            }
            if (in_array($module, $exist)) {
                continue;
            }
            $exist[] = $module;
            $route->module($path, function () use (&$menuItems, $module, $route) {
                $instance = $route->moduleInstance($module, app(HttpContext::class));
                if (method_exists($instance, 'adminMenu')) {
                    $menuItems = array_merge($menuItems, call_user_func([$instance, 'adminMenu']));
                }
            }, $modules);
        }
        return $menuItems;
    }
}