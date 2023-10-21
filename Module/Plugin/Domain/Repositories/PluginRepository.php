<?php
declare(strict_types=1);
namespace Module\Plugin\Domain\Repositories;

use Composer\Autoload\ClassLoader;
use Module\Plugin\Domain\Entities\PluginEntity;
use Module\Plugin\Domain\IPlugin;
use Module\Plugin\Domain\Models\PluginModel;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Html\InputHelper;
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
        return self::settingForm($model);
    }

    private static function settingForm(PluginModel $model) {
        $data = self::loadPlugin($model['path']);
        if (empty($data) || !isset($data['configs'])) {
            return [];
        }
        return InputHelper::patch($data['configs'], (array)$model->configs);
    }

    public static function settingSave(int $id, Request $input) {
        $model = PluginModel::findOrThrow($id);
        $data = self::loadPlugin($model['path']);
        if (empty($data) || !isset($data['configs'])) {
            return;
        }
        $model->configs = InputHelper::value($data['configs'], $input);
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
                return true;
            }
            $data['path'] = $item->getRelative($root);
            $items[] = $data;
            return true;
        });
        $exist = PluginEntity::query()->pluck('id', 'path');
        $add = [];
        $update = [];
        foreach ($items as $item) {
            $data = [
                'name' => $item['name'],
                'description' => $item['description'] ?? '',
                'author' => $item['author'] ?? '',
                'version' => $item['version'] ?? '',
                'path' => $item['path'],
            ];
            if (isset($exist[$item['path']])) {
                $update[] = $exist[$item['path']];
                PluginEntity::where('id', $exist[$item['path']])
                    ->update($data);
                continue;
            }
            $add[] = $data;
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

    public static function install(int $id, array $postData = []) {
        $model = PluginModel::findOrThrow($id);
        $data = self::loadPlugin($model['path']);
        if (empty($data)) {
            throw new \Exception('plugin is error');
        }
        $inputItems = $data['configs'] ?? [];
        if (!empty($inputItems)) {
            $configs = $model->configs;
            if (!empty($postData)) {
                $model->configs = InputHelper::value($inputItems, $postData);
            } else if (empty($configs)) {
                return InputHelper::patch($inputItems, $configs);
            }
        }
        $model->status = 1;
        $model->save();
        return $model;
    }

    public static function uninstall(int $id) {
        PluginEntity::where('id', $id)->update([
            'status' => 0,
            'configs' => '',
        ]);
    }

    public static function execute(int $id) {
        $model = PluginModel::findOrThrow($id);
        $root = self::root();
        $loader = new ClassLoader((string)$root);
        $loader->register();
        self::invokePlugin($loader, $root->directory($model->path), $model->configs);
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