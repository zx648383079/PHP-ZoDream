<?php
declare(strict_types=1);
namespace Module\Template\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Providers\StorageProvider;
use Module\Template\Domain\Entities\ThemeComponentEntity;
use Module\Template\Domain\Model\ThemeComponentModel;

final class ComponentRepository {

    public static function storage(): StorageProvider {
        return StorageProvider::privateStore();
    }

    public static function getManageList(string $keywords = '', int $user = 0, int $category = 0)
    {
        return ThemeComponentModel::with('user', 'category')->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['name'], true, '', $keywords);
        })->when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })->when($category > 0, function ($query) use ($category) {
            $query->where('cat_id', $category);
        })->orderBy('id', 'desc')->page();
    }

    public static function manageGet(int $id)
    {
        return ThemeComponentEntity::findOrThrow($id);
    }

    public static function manageSave(array $data)
    {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? ThemeComponentEntity::query()->where('id', $id)->first() : new ThemeComponentEntity();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        $model->load($data, ['user_id']);
        if ($model->isNewRecord) {
            $model->user_id = auth()->id();
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function manageRemove(int $id)
    {
        $model = ThemeComponentEntity::findOrThrow($id);
        $model->delete();
    }

    public static function manageUpload(array $file)
    {
        return self::storage()->addFile($file);
    }

    public static function selfList(string $keywords = '',int $type = 0, int $category = 0)
    {
        return ThemeComponentModel::with('category')
            ->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['name'], true, '', $keywords);
        })->where('user_id', auth()->id())->when($category > 0, function ($query) use ($category) {
            $query->where('cat_id', $category);
        })->where('type', $type)->orderBy('id', 'desc')->page();
    }

    public static function selfGet(int $id)
    {
        $model = ThemeComponentModel::findWithAuth($id);
        if (empty($model)) {
            throw new \Exception('不存在');
        }
        return $model;
    }

    public static function selfSave(array $data)
    {
        $userId = auth()->id();
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? ThemeComponentEntity::query()->where('id', $id)
            ->where('user_id', $userId)->first() : new ThemeComponentEntity();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        $model->load($data, ['user_id']);
        $model->user_id = $userId;
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function selfRemove(int $id)
    {
        $model = self::selfGet($id);
        $model->delete();
    }

    public static function selfUpload(array $file)
    {
        return self::storage()->addFile($file);
    }

    public static function getList(string $keywords = '', int $user = 0, int $category = 0,
                                   string $sort = 'created_at',
                                   string|int|bool $order = 'desc')
    {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, ['id', 'price', 'created_at']);
        return ThemeComponentModel::with('category')
            ->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['name'], true, '', $keywords);
        })->when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })->when($category > 0, function ($query) use ($category) {
            $query->where('cat_id', $category);
        })->orderBy($sort, $order)->page();
    }

    public static function suggestion(string $keywords = '') {
        return ThemeComponentModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->limit(4)->get();
    }

    public static function dialogList(string $keywords = '', int $category = 0,
                                      int $type = 0, array|int $id = 0)
    {
        return ThemeComponentModel::when(!empty($id), function ($query) use ($id) {
                if (is_array($id)) {
                    $query->whereIn('id', $id);
                    return;
                }
                $query->where('id', $id);
            })
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['name'], true, '', $keywords);
            })->where('type', $type)->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->page();
    }
}