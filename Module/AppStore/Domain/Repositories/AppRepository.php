<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Repositories;

use Domain\Constants;
use Domain\Model\SearchModel;
use Domain\Providers\ActionLogProvider;
use Domain\Providers\CommentProvider;
use Domain\Providers\StorageProvider;
use Domain\Providers\TagProvider;
use Exception;
use Infrastructure\HtmlExpand;
use Module\AppStore\Domain\Models\AppFileModel;
use Module\AppStore\Domain\Models\AppModel;
use Module\AppStore\Domain\Models\AppVersionModel;
use Zodream\Database\Contracts\SqlBuilder;

final class AppRepository {

    const BASE_KEY = 'app';
    const SOFTWARE_PAGE_FILED = [
        'id', 'user_id', 'cat_id', 'name', 'description', 'icon',
        'is_free',
        'is_open_source',
        'comment_count',
        'download_count',
        'view_count',
        'score',
        'updated_at',
        'created_at',];

    public static function comment(): CommentProvider {
        return new CommentProvider(self::BASE_KEY);
    }

    public static function log(): ActionLogProvider {
        return new ActionLogProvider(self::BASE_KEY);
    }

    public static function tag(): TagProvider {
        return new TagProvider(self::BASE_KEY);
    }

    public static function storage(): StorageProvider {
        return StorageProvider::privateStore();
    }

    public static function getManageList(
        string $keywords = '',
        int $user = 0, int $category = 0) {
        return AppModel::with('user', 'category')
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['name', 'package_name'], true, '', $keywords);
            })
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')
            ->page();
    }

    public static function getList(
        string $keywords = '',
        int $category = 0,
        string|array $sort = 'created_at',
        string|int|bool $order = 'desc') {
        $page = self::getListQuery($keywords, $category, $sort, $order)
            ->page();
        foreach ($page as $item) {
            $item['size'] = AppFileModel::where('app_id', $item['id'])
                ->orderBy('version_id', 'desc')->orderBy('created_at', 'desc')
                ->value('size');
        }
        return $page;
    }

    public static function getLimitList(
        int $count,
        string $keywords = '',
        int $category = 0,
        string|array $sort = 'created_at',
        string|int|bool $order = 'desc') {
        $items = self::getListQuery($keywords, $category, $sort, $order)
            ->limit($count)->get();
        foreach ($items as $item) {
            $item['size'] = AppFileModel::where('app_id', $item['id'])
                ->orderBy('version_id', 'desc')->orderBy('created_at', 'desc')
                ->value('size');
        }
        return $items;
    }

    private static function getListQuery(
        string $keywords = '',
        int $category = 0,
        string|array $sort = 'created_at',
        string|int|bool $order = 'desc'): SqlBuilder {
        return AppModel::with('category')
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['name', 'package_name'], true, '', $keywords);
            })
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->orderBy('id', 'desc')
            ->when(!empty($sort), function ($query) use ($sort, $order) {
                if ($sort === 'new') {
                    $query->orderBy('created_at', 'desc');
                    return;
                }
                if ($sort === 'free') {
                    $query->where('is_free', 1);
                    return;
                }
                if ($sort === 'hot') {
                    $query->orderBy('download_count', 'desc');
                    return;
                }
                list($sort, $order) = SearchModel::checkSortOrder($sort, $order, [
                    'id', 'created_at', 'download_count', 'view_count', 'comment_count'
                ]);
                $query->orderBy($sort, $order);
            })
            ->select(self::SOFTWARE_PAGE_FILED);
    }

    public static function get(int $id) {
        $model = AppModel::with('user')->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('note error');
        }
        return $model;
    }

    public static function getEdit(int $id, int $version = 0) {
        $model = self::get($id);
        if ($version > 0) {
            $model->version = AppVersionModel::where('app_id', $id)
                ->where('id', $version)->first();
        }
        $model->tags = self::tag()->getTags($id);
        return $model;
    }

    public static function getSelf(int $id) {
        $model = AppModel::findWithAuth($id);
        if (empty($model)) {
            throw new Exception('应用不存在');
        }
        return $model;
    }


    public static function save(array $data, array $tags = []) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? self::getSelf($id) : new AppModel();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        self::tag()->bindTag($model->id, $tags);
        return $model;
    }

    public static function remove(int $id) {
        AppModel::where('id', $id)->delete();
        self::tag()->removeLink($id);
        AppVersionModel::where('app_id', $id)->delete();
        AppFileModel::where('app_id', $id)->delete();
    }

    public static function removeSelf(int $id) {
        self::getSelf($id);
        self::remove($id);
    }

    public static function versionList(int $software, string $keywords = '') {
        return AppVersionModel::with('files')->where('app_id', $software)
            ->when(!empty($keywords), function($query) use ($keywords) {
                SearchModel::searchWhere($query, ['name'], false, '', $keywords);
            })
            ->orderBy('id', 'asc')
            ->page();
    }

    public static function versionCreate(array $data) {
        static::getSelf($data['app_id']);
        if (AppVersionModel::where('app_id', $data['app_id'])->where('name', $data['name'])->count()) {
            throw new Exception('版本号已存在');
        }
        $model = new AppVersionModel();
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function versionRemove(int $id) {
        AppVersionModel::where('id', $id)->delete();
        AppFileModel::where('version_id', $id)->delete();
    }


    public static function suggestion(string $keywords) {
        return AppModel::when(!empty($keywords), function($query) use ($keywords) {
            SearchModel::searchWhere($query, ['name'], false, '', $keywords);
        })->limit(4)->pluck('name');
    }

    public static function packageList(int $software, int $version = 0, string $keywords = '')
    {
        return AppFileModel::where('app_id', $software)
            ->when(!empty($keywords), function($query) use ($keywords) {
                SearchModel::searchWhere($query, ['os', 'name'], false, '', $keywords);
            })
            ->when($version > 0, function($query) use ($version) {
                $query->where('version_id', $version);
            })
            ->orderBy('id', 'asc')
            ->page();
    }

    public static function packageSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = AppFileModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if ($model->url_type < 1) {
            self::storage()->addQuote($model->url, Constants::TYPE_APP_STORE, $model->app_id);
        }
        return $model;
    }

    public static function packageRemove(int $id) {
        AppFileModel::where('id', $id)->delete();
    }

    public static function getFull(int $id, int $version = 0) {
        $model = AppModel::findOrThrow($id, '应用不存在');
        $data = $model->toArray();
        $data['content'] = HtmlExpand::toHtml($model->content, true, false);
        $data['category'] = $model->category;
        $data['user'] = $model->user;
        $data['version'] = AppVersionModel::when($version > 0, function ($query) use ($version) {
            $query->where('id', $version);
        }, function ($query) {
            $query->orderBy('id', 'desc');
        })->where('app_id', $id)->first();
        if (!$data['version']) {
            throw new Exception('应用为空或没有相关版本');
        }
        $data['packages'] = AppFileModel::where('app_id', $id)
            ->where('version_id', $data['version']->id)->get();
        $data['size'] = empty($data['packages']) ? 0 : $data['packages'][0]['size'];
        return $data;
    }

    public static function download(int $id): string {
        $model = AppFileModel::where('id', $id)->first();
        if (empty($model) || $model->url_type > 0) {
            throw new Exception('文件不存在');
        }
        return $model->url;
    }

    public static function check(array $items) {
        $data = [];
        foreach ($items as $packageName => $version) {
            if (is_array($version)) {
                if (isset($version['package_name'])) {
                    $packageName = $version['package_name'];
                }
                $version = $version['version'];
            }
            if (empty($packageName) || empty($version)) {
                continue;
            }
            $package = AppModel::where('package_name', $packageName)
                ->first();
            if (empty($package)) {
                continue;
            }
            $lastVersion = AppVersionModel::where('app_id', $package->id)
                ->orderBy('id', 'desc')->first();
            if (empty($lastVersion) || $lastVersion->name === $version) {
                continue;
            }
            $file = AppFileModel::where('app_id', $package->id)
                ->where('version_id', $lastVersion->id)
                ->orderBy('url_type', 'asc')
                ->first();
            if (empty($file)) {
                continue;
            }
            $data[] = [
                'package' => $package,
                'version' => $lastVersion,
                'file' => $file
            ];
        }
        return $data;
    }

}
