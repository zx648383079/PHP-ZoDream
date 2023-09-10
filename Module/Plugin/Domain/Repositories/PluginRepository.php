<?php
namespace Module\Plugin\Domain\Repositories;

use Composer\Autoload\ClassLoader;
use Module\Plugin\Domain\Entities\PluginEntity;
use Module\Plugin\Domain\IPlugin;
use Module\Plugin\Domain\Models\PluginModel;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Html\Input;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

final class PluginRepository {

    public static function root(): Directory {
        return app_path()->directory('data/plugins');
    }

    public static function getList(string $keywords = '') {
        return PluginEntity::query()->page();
    }

    public static function setting(int $id) {
        $model = PluginModel::findOrThrow($id);
        $data = self::loadPlugin($model['path']);
        if (empty($data) || !isset($data['configs'])) {
            return [];
        }
        $items = [];
        $configs = $model->configs;
        foreach ($data['configs'] as $item) {
            if (!($item instanceof Input) || empty($item->name)) {
                continue;
            }
            if (isset($configs[$item->name])) {
                $item->value($configs[$item->name]);
            }
            $items[] = $item;
        }
        return $items;
    }

    public static function settingSave(int $id, Request $input) {
        $model = PluginModel::findOrThrow($id);
        $data = self::loadPlugin($model['path']);
        if (empty($data) || !isset($data['configs'])) {
            return;
        }
        $configs = $model->configs;
        foreach ($data['configs'] as $item) {
            if (!($item instanceof Input) || empty($item->name)) {
                continue;
            }
            if ($input->has($item->name)) {
                $configs[$item->name] = $item->filter($input->get($item->name));
            }
        }
        $model->configs = $configs;
        $model->save();
    }

    public static function toggle(int $id) {
        $model = PluginModel::findOrThrow($id);
        $model->status = $model->status > 0 ? 0  : 1;
        $model->save();
        return $model;
    }

    public static function sync(): void {
        $items = [];
        $root = self::root();
        $root->mapDeep(function ($item) use (&$items, $root) {
            if (!($item instanceof Directory)) {
                return true;
            }
            $file = $item->file('index.php');
            if (!$file->exist()) {
                return true;
            }
            $data = require (string)$file;
            if (empty($data) || !is_array($data)) {
                return false;
            }
            $data['path'] = $item->getRelative($root);
            $items[] = $data;
            return false;
        });
        $exist = PluginEntity::query()->pluck('id', 'path');
        $add = [];
        $update = [];
        foreach ($items as $item) {
            $data = [
                // TODO
            ];
            if (isset($exist[$item['path']])) {
                $update[] = $exist[$item['path']];
                PluginEntity::where('id', $exist[$item['path']])
                    ->update($data);
                continue;
            }
            $add[] = $add;
        }
        if (!empty($add)) {
            PluginEntity::query()->insert($add);
        }
        $del = array_diff(array_values($exist), $update);
        if (!empty($del)) {
            PluginEntity::whereIn('id', $del)
                ->delete();
        }
    }

    public static function run() {
        $root = self::root();
        $loader = new ClassLoader((string)$root);
        $loader->register();

        // TODO
    }

    private static function loadPlugin(string $path): array {
        $file = self::root()->combine($path, 'index.php');
        if ($file instanceof File && $file->exist()) {
            return require (string)$file;
        }
        return [];
    }

    private static function invokePlugin(ClassLoader $loader, Directory $folder, array $configs = []): void {
        $data = require (string)$folder->file('index.php');
        if (!isset($data['entry'])) {
            return;
        }
        if (!isset($data['autoload'])) {
            self::addAutoload($loader, $folder, $data['autoload']);
        }
        if (!class_exists($data['entry'])) {
            return;
        }
        $instance = app()->make($data['entry']);
        if (!($instance instanceof IPlugin)) {
            return;
        }
        try {
            $instance->initiate();
            $instance($configs);
            $instance->destroy();
        } catch (\Exception $ex) {
            // TODO 记录错误
        }
    }

    private static function addAutoload(ClassLoader $loader, Directory $folder, array $items) {
        foreach ($items as $path => $prefix) {
            if (empty($path) || $path === '.' || $path === './') {
                $loader->add($prefix, (string)$folder);
                continue;
            }
            $loader->add($prefix, (string)$folder->directory($path));
        }
    }
}