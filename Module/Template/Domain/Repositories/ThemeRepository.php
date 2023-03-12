<?php
declare(strict_types=1);
namespace Module\Template\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Template\Domain\Model\ThemeModel;
use Module\Template\Domain\Model\ThemePageModel;
use Module\Template\Domain\Model\ThemeStyleModel;
use Module\Template\Domain\Model\ThemeWeightModel;
use Module\Template\Domain\VisualEditor\VisualFactory;
use Zodream\Disk\Directory;
use Zodream\Disk\FileObject;
use Zodream\Helpers\Json;

final class ThemeRepository {

    public static function getList(string $keywords = '') {
        return ThemeModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->orderBy('id', 'desc')
            ->page();
    }

    public static function pageList(int $theme, string $keywords = '') {
        return ThemeModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('theme_id', $theme)->orderBy('id', 'desc')
            ->page();
    }

    public static function styleList(int $theme) {
        return ThemeStyleModel::where('theme_id', $theme)->get();
    }

    public static function themeIsInstalled(string $name) {
        return ThemeModel::where('name', $name)->count() > 0;
    }

    public static function pageIsInstalled(string $name, int $theme_id) {
        return ThemePageModel::where('name', $name)->where('theme_id', $theme_id)->count() > 0;
    }

    public static function styleIsInstalled(string $name, int $theme_id) {
        return ThemeStyleModel::where('name', $name)->where('theme_id', $theme_id)->count() > 0;
    }

    public static function weightIsInstalled(string $name, int $theme_id) {
        return ThemeWeightModel::where('name', $name)->where('theme_id', $theme_id)->count() > 0;
    }

    /**
     * 获取分好组的组件
     * @param int $theme_id
     * @return array
     */
    public static function weightGroups(int $theme_id) {
        $data = ThemeWeightModel::where('theme_id', $theme_id)->get();
        $args = [
            ['id' => 1, 'name' => '基本', 'items' => []],
            ['id' => 2, 'name' => '高级', 'items' => []],
        ];
        foreach ($data as $item) {
            $item['thumb'] = url('./admin/theme/asset', ['folder' => $item->path, 'file' => $item->thumb]);
            $args[$item->type]['items'][] = $item;
        }
        return array_values($args);
    }


    public static function installAllThemes() {
        $data = self::loadThemes();
        foreach ($data as $item) {
            self::installTheme($item);
        }
    }

    /**
     * 保存一个主题
     * @param array $data
     * @return void
     */
    public static function installTheme(array $data) {
        $model = new ThemeModel($data);
        $id = ThemeModel::where('name', $data['name'])->value('id');
        if ($id > 0) {
            $model->id = $id;
            $model->isNewRecord = false;
        } else{
            $model->save();
        }
        foreach ($data['pages'] as $page) {
            if (self::pageIsInstalled($page['name'], $model->id)) {
                continue;
            }
            $page['theme_id'] = $model->id;
            ThemePageModel::create($page);
        }
        foreach ($data['weights'] as $weight) {
            if (self::weightIsInstalled($weight['name'], $model->id)) {
                continue;
            }
            $weight['theme_id'] = $model->id;
            ThemeWeightModel::create($weight);
        }
        foreach ($data['styles'] as $style) {
            if (self::styleIsInstalled($style['name'], $model->id)) {
                continue;
            }
            $style['theme_id'] = $model->id;
            ThemeStyleModel::create($style);
        }
        /** @var Directory $root */
        $root = VisualFactory::templateFolder($data['path']);
        $themeAsset = public_path()->directory(sprintf('assets/themes/%s', $data['name']));
        $themeAsset->create();
        // TODO 处理js、css文件
        foreach ((array)$data['assets'] as $fileName) {
            if (empty($fileName)) {
                continue;
            }
            $file = $root->child($fileName);
            $file->copy($themeAsset->child($fileName));
        }
    }

    /**
     * @return array[]
     */
    public static function loadThemes(): array {
        return self::mapFolder(VisualFactory::templateFolder(), function ($item) {
            if ($item->hasFile('theme.json')) {
                return [self::createTheme($item)];
            }
            return false;
        });
    }

    /**
     * 遍历所有文件夹
     * @param Directory $dir
     * @param callable $cb
     * @return array
     */
    protected static function mapFolder(Directory $dir, callable $cb) {
        $item = $cb($dir);
        if ($item !== false) {
            return $item;
        }
        $items = [];
        $dir->map(function (FileObject $file) use (&$items, $cb) {
            if (!($file instanceof Directory)) {
                return;
            }
            $items = array_merge($items, self::mapFolder($file, $cb));
        });
        return $items;
    }

    /**
     * 获取一个主题下的所有内容
     * @param Directory $folder
     * @return array{path:string,name: string,thumb:string,keywords: string,description:string,author:string,since:string,version:string,copyright:string,email:string,url:string,pages:array,styles:array,weights:array}
     * @throws \Exception
     */
    public static function createTheme(Directory $folder): array {
        $data = Json::decode($folder->childFile('theme.json')->read());
        $data['path'] = $folder->getRelative(VisualFactory::templateFolder());
        $data['pages'] = array_map(function ($item) use ($data) {
            $item['path'] = $data['path'].'/'.ltrim($item['path'], '/');
            return $item;
        }, $data['pages']);
        $data['styles'] = isset($data['styles']) ? array_map(function ($item) use ($data) {
            $item['path'] = $data['path'].'/'.ltrim($item['path'], '/');
            return $item;
        }, $data['styles']) : [];
        $data['weights'] = self::getWeight($data, $folder);
        return $data;
    }

    /**
     * 获取文件下的所有组件
     * @param array $data
     * @param Directory $folder
     * @return array{path: string,name: string,thumb:string,keywords: string,description:string,author:string,since:string,version:string,copyright:string,email:string,editable:bool}[]
     * @throws \Exception
     */
    private static function getWeight(array $data, Directory $folder): array {
        if (!isset($data['weights'])) {
            return [];
        }
        if (is_array($data['weights'])) {
            return array_map(function ($item) use ($data) {
                $item['path'] = $data['path'].'/'.ltrim($item['path'], '/');
                return $item;
            }, $data['weights']);
        }
        return self::mapFolder($folder->directory($data['weights']), function (Directory $item) {
            if (!$item->hasFile('weight.json')) {
                return false;
            }
            $args = Json::decode($item->childFile('weight.json')->read());
            $args['path'] = $item->getRelative(VisualFactory::templateFolder());
            return [$args];
        });
    }

}