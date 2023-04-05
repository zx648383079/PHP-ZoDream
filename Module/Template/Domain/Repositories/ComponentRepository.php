<?php
declare(strict_types=1);
namespace Module\Template\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Providers\StorageProvider;
use Module\Template\Domain\Entities\ThemeComponentEntity;
use Module\Template\Domain\Model\SiteComponentModel;
use Module\Template\Domain\Model\ThemeComponentModel;
use Zodream\Disk\Directory;

final class ComponentRepository {

    const STATUS_NONE = 0;
    const STATUS_APPROVED = 1;

    public static function storage(): StorageProvider {
        return StorageProvider::privateStore();
    }
    public static function root(): Directory {
        return app_path()->directory('data/visual');
    }

    public static function assetRoot(): Directory {
        return public_path()->directory('assets/themes');
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
        $oldStatus = intval($model->status);
        $model->load($data, ['user_id']);
        if ($model->isNewRecord) {
            $model->user_id = auth()->id();
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        self::unpackFile($model);
        if ($oldStatus !== intval($model->status) && $oldStatus !== self::STATUS_APPROVED) {
            SiteComponentModel::where('component_id')
                ->update([
                    'cat_id' => $model->cat_id,
                    'name' => $model->name,
                    'description' => $model->description,
                    'thumb' => $model->thumb,
                    'type' => $model->type,
                    'author' => $model->author,
                    'version' => $model->version,
                    'path' => $model->path,
                    'editable' => $model->editable,
                    'alias_name' => $model->alias_name
                ]);
        }
        return $model;
    }

    public static function manageRemove(array|int $id)
    {
        ThemeComponentEntity::whereIn('id', (array)$id)->delete();
    }

    public static function manageReview(array|int $id, array $items)
    {
        $data = [];
        $maps = ['status'];
        foreach ($items as $key => $val) {
            $action = is_int($key) ? $val : $key;
            if (!in_array($action, $maps)) {
                continue;
            }
            if (!is_int($key)) {
                $data[$action] = $val;
                continue;
            }
            $data[] = sprintf('`%s` = CASE WHEN `%s` = 1 THEN 0 ELSE 1 END', $action, $action);
        }
        if (empty($data)) {
            return;
        }
        ThemeComponentEntity::query()->whereIn('id', (array)$id)->update($data);
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

    public static function selfSave(array $data) {
        $userId = auth()->id();
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? ThemeComponentEntity::query()->where('id', $id)
            ->where('user_id', $userId)->first() : new ThemeComponentEntity();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        $oldPath = $model->path;
        $model->load($data, ['user_id']);
        $model->user_id = $userId;
        if ($oldPath !== $model->path) {
            $model->status = self::STATUS_NONE;
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        // self::unpackFile($model);
        return $model;
    }

    protected static function unpackFile(ThemeComponentEntity $model) {
        try {
            $file = self::storage()->getFile($model->path, true);
        } catch (\Exception) {
            return;
        }
        $data = ThemeRepository::load($file->getName(), $file,
            self::root()->directory(sprintf('%d_%d', $model->user_id,  $model->id)));
        foreach ($data as $item) {
            $model->type = $item['type'];
            $model->alias_name = $item['name'];
            $model->editable = $item['editable'] === true;
            $model->path = $item['entry'];
            if (isset($item['version'])) {
                $model->version = $item['version'];
            }
            $model->dependencies = $item['dependencies'] ?? [];
            $model->save();
            return;
        }
    }

    public static function selfRemove(int $id) {
        $model = self::selfGet($id);
        $model->delete();
        self::removeFile($model);
    }

    protected static function removeFile(ThemeComponentEntity $model) {
        $exist = SiteComponentModel::where('component_id', $model->id)
            ->where('path', $model->path)->count() > 0;
        if ($exist) {
            return;
        }
        self::root()->directory($model->path)->delete();
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
            $query->where('cat_id', CategoryRepository::getAllChildrenId($category));
        })->where('status', self::STATUS_APPROVED)->orderBy($sort, $order)->page();
    }

    public static function suggestion(string $keywords = '') {
        return ThemeComponentModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->where('status', self::STATUS_APPROVED)->limit(4)->get();
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
            })->where(function ($query) {
                $query->where('status', self::STATUS_APPROVED)
                    ->orWhere('user_id', auth()->id());
            })->orderBy('status', 'desc')->orderBy('created_at', 'desc')->page();
    }

    public static function recommend(int $type) {
        return ThemeComponentModel::with('category')->where('type', $type)
            ->where('status', self::STATUS_APPROVED)
            ->limit(10)->get();
    }

    public static function selfImport(array $file)
    {
        $data = self::storage()->insertFile($file, true);
        ThemeRepository::importFileLog($data);
    }
}