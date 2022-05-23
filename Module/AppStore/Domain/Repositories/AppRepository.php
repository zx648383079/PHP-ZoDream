<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Providers\ActionLogProvider;
use Domain\Providers\CommentProvider;
use Domain\Providers\TagProvider;
use Exception;
use Module\AppStore\Domain\Models\AppFileModel;
use Module\AppStore\Domain\Models\AppModel;
use Module\AppStore\Domain\Models\AppVersionModel;

final class AppRepository {

    const BASE_KEY = 'app';

    public static function comment(): CommentProvider {
        return new CommentProvider(self::BASE_KEY);
    }

    public static function log(): ActionLogProvider {
        return new ActionLogProvider(self::BASE_KEY);
    }

    public static function tag(): TagProvider {
        return new TagProvider(self::BASE_KEY);
    }

    public static function getManageList(
        string $keywords = '',
        int $user = 0, int $category = 0) {
        return AppModel::when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['name'], true, '', $keywords);
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
        int $user = 0, int $id = 0) {
        return AppModel::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })
            ->when($id > 0, function($query) use ($id) {
                $query->where('id', $id);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')
            ->page();
    }

    public static function get(int $id) {
        $model = AppModel::with('user')->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('note error');
        }
        return $model;
    }

    public static function getSelf(int $id) {
        $model = AppModel::findWithAuth($id);
        if (empty($model)) {
            throw new Exception('应用不存在');
        }
        return $model;
    }


    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? self::getSelf($id) : new AppModel();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        AppModel::where('id', $id)->delete();
    }

    public static function removeSelf(int $id) {
        self::getSelf($id)->delete();
    }

    public static function versionList(int $software, string $keywords = '') {
        return AppVersionModel::where('app_id', $software)
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
        return AppVersionModel::where('app_id', $software)
            ->when(!empty($keywords), function($query) use ($keywords) {
                SearchModel::searchWhere($query, ['os'], false, '', $keywords);
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
        return $model;
    }

    public static function packageRemove(int $id) {
        AppFileModel::where('id', $id)->delete();
    }

}
