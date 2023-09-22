<?php
declare(strict_types=1);
namespace Domain;

use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\ModuleRoute;

class AdminMenu {

    public static function all(): array {
        if (app()->isDebug()) {
            return static::getAll();
        }
        $cacheFile = app_path('data/admin_menu.php');
        if ($cacheFile->exist()) {
            $data = require (string)$cacheFile;
        } else {
            $data = static::getAll();
            $cacheFile->write('<?php return '.var_export($data, true).';');
        }
        return $data;
    }

    protected static function getAll(): array {
        $menus = self::moduleMenu();
        array_unshift($menus, [
            '首页',
            '/admin.php',
            'fa fa-home',
        ]);
        return $menus;
    }

    protected static function moduleMenu(): array {
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

    /**
     * 组成一个菜单项
     * @param string $name
     * @param string $icon
     * @param string $url
     * @param array $children
     * @return array
     */
    public static function build(string $name, string $icon, string $url = '', array $children = []): array {
        return [$name, empty($url) ? false : url($url), $icon, $children];
    }
}