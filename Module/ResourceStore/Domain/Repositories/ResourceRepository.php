<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Providers\ActionLogProvider;
use Domain\Providers\CommentProvider;
use Domain\Providers\TagProvider;
use Exception;
use Module\ResourceStore\Domain\Models\ResourceFileModel;
use Module\ResourceStore\Domain\Models\ResourceModel;


class ResourceRepository {
    const LOG_TYPE_RES = 0;
    const LOG_ACTION_BUY = 66;
    const LOG_ACTION_DOWNLOAD = 1;
    const BASE_KEY = 'res';

    public static function comment(): CommentProvider {
        return new CommentProvider(self::BASE_KEY);
    }

    public static function log(): ActionLogProvider {
        return new ActionLogProvider(self::BASE_KEY);
    }

    public static function tag(): TagProvider {
        return new TagProvider(self::BASE_KEY);
    }

    public static function getList(string $keywords = '', int $category = 0,
                                   int $user = 0, array|string $sort = '', string $tag = '') {
        return ResourceModel::with('user', 'category')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                if (is_array($sort)) {
                    // 增加直接放id
                    $query->whereIn('id', $sort)->orderBy('created_at', 'desc');
                    return;
                }
                if ($sort === 'new') {
                    $query->orderBy('created_at', 'desc');
                    return;
                }
                if ($sort === 'hot') {
                    $query->orderBy('download_count', 'desc');
                }
            })->when(!empty($keywords), function ($query) use ($keywords)  {
                SearchModel::searchWhere($query, ['title'], true, '', $keywords);
            })->when(!empty($tag), function ($query) use ($tag) {
                $ids = static::tag()->searchTag($tag);
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('id', $ids);
            })->page();
    }

    public static function get(int $id): ResourceModel {
        return ResourceModel::findOrThrow($id, '资源不存在');
    }

    public static function getEdit(int $id) {
        $model = self::get($id);
        $model->tags = self::tag()->getTags($id);
        $model->files = ResourceFileModel::where('res_id', $id)->get();
        return $model;
    }

    public static function getFull(int $id) {
        $model = static::get($id);
        ResourceModel::query()->where('id', $id)->updateIncrement('click_count');
        $_ = $model->user;
        $_ = $model->category;
        $model->tags = static::tag()->getTags($id);
        $model->files = ResourceFileModel::where('res_id', $id)->get();
        return $model;
    }

    public static function save(array $data, array $tags, array $files) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? ResourceModel::query()->where('id', $id)
            ->where('user_id', auth()->id())->first() : new ResourceModel();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        $model->load($data, ['user_id']);
        $model->user_id = auth()->id();
        if (!$model->saveIgnoreUpdate()) {
            throw new \Exception($model->getFirstError());
        }
        static::tag()->bindTag($model->id, $tags);
        $fileId = [];
        foreach ($files as $item) {
            $item['res_id'] = $model->id;
            $fileModel = self::fileSave($item);
            $fileId[] = $fileModel->id;
            if ($fileModel->file_type > 0) {
                continue;
            }
            $file = UploadRepository::file($fileModel);
            if ($file->exist()) {
                $model->size = $file->size();
            }
            UploadRepository::unzipFile($fileModel);
        }
        ResourceFileModel::where('res_id', $model->id)
            ->whereNotIn('id', $fileId)->delete();
        return $model;
    }

    public static function remove(int $id) {
        $model = ResourceModel::query()->where('id', $id)->first();
        self::removeResource($model);
    }

    private static function removeResource(?ResourceModel $model) {
        if (empty($model)) {
            throw new \Exception('资源不存在');
        }
        $items = ResourceFileModel::where('res_id', $model->id)->get();
        foreach ($items as $item) {
            self::removeFile($item);
        }
    }

    private static function removeFile(ResourceFileModel $model) {
        if ($model->file_type > 0) {
            $model->delete();
            return;
        }
        $file = UploadRepository::file($model);
        if ($file->exist()) {
            $file->delete();
        }
        $folder = UploadRepository::resourceFolder($model->id);
        if ($folder->exist()) {
            $folder->delete();
        }
        $model->delete();
    }

    public static function getCatalog(int $id) {
        $model = ResourceModel::findOrThrow($id, '资源不存在');
        return UploadRepository::fileMap($model);
    }

    public static function suggestion(string $keywords) {
        return ResourceModel::when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['title'], false, '', $keywords);
        })->limit(4)->asArray()->get('id', 'title');
    }

    public static function getManageList(string $keywords = '', int $user = 0, int $category = 0) {
        return ResourceModel::with('user', 'category')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->when(!empty($keywords), function ($query) use ($keywords)  {
                SearchModel::searchWhere($query, ['title'], true, '', $keywords);
            })->page();
    }

    public static function getSelf(int $id) {
        $model = ResourceModel::findWithAuth($id);
        if (empty($model)) {
            throw new Exception('资源不存在');
        }
        return $model;
    }

    public static function removeSelf(int $id) {
        self::removeResource(self::getSelf($id));
    }

    public static function fileList(int $res_id, string $keywords = '') {
        return ResourceFileModel::where('res_id', $res_id)
            ->when(!empty($keywords), function($query) use ($keywords) {
                // SearchModel::searchWhere($query, ['name'], false, '', $keywords);
            })
            ->orderBy('id', 'asc')
            ->page();
    }

    public static function fileSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ResourceFileModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function fileRemove(int $id) {
        self::removeFile(ResourceFileModel::findOrThrow($id, '文件不存在'));
    }

}