<?php
declare(strict_types=1);
namespace Domain;

use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\ModuleRoute;

class MenuLoader {

    public static function loadAdmin(): array {
        return static::loadStorage('admin_menu', function () {
            return static::load('adminMenu', [
                static::build(__('Home'), 'fa fa-home', '/admin.php',)
            ]);
        });
    }

    public static function loadMember(): array {
        return static::loadStorage('member_menu', function () {
            return static::load('memberMenu', [
                static::build(__('Overview'), 'fa fa-home', '/account.php')
            ]);
        });
    }

    /**
     * 从缓存文件中获取
     * @param string $fileName
     * @param callable $cb
     * @return array
     * @throws \Exception
     */
    public static function loadStorage(string $fileName, callable $cb): array {
        if (app()->isDebug()) {
            return call_user_func($cb);
        }
        $cacheFile = app_path(sprintf('data/%s.php', $fileName));
        if ($cacheFile->exist()) {
            $data = require (string)$cacheFile;
        } else {
            $data = call_user_func($cb);
            $cacheFile->write('<?php return '.var_export($data, true).';');
        }
        return $data;
    }

    public static function load(string $method, array $beginItems = [], array $endItems = []): array {
        return array_merge($beginItems, static::loadModule($method), $endItems);
    }

    protected static function loadModule(string $method): array {
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
            $route->module($path, function () use (&$menuItems, $module, $route, $method) {
                $instance = $route->moduleInstance($module, app(HttpContext::class));
                if (method_exists($instance, $method)) {
                    $menuItems = array_merge($menuItems, call_user_func([$instance, $method]));
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
        return [
            'label' => $name,
            'url' => empty($url) ? false : url($url),
            'icon' => $icon,
            'children' => $children
        ];
    }
}