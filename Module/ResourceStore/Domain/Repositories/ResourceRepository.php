<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Providers\ActionLogProvider;
use Domain\Providers\CommentProvider;
use Domain\Providers\TagProvider;
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
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->when(!empty($tag), function ($query) use ($tag) {
                $ids = static::tag()->searchTag($tag);
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('id', $ids);
            })->page();
    }

    public static function get(int $id) {
        return ResourceModel::findOrThrow($id, '资源不存在');
    }

    public static function getFull(int $id) {
        $model = static::get($id);
        ResourceModel::query()->where('id', $id)->updateIncrement('click_count');
        $model->user;
        $model->category;
        $model->tags = static::tag()->getTags($id);
        return $model;
    }

    public static function save(array $data, array $tags) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? ResourceModel::query()->where('id', $id)
            ->where('user_id', auth()->id())->first() : new ResourceModel();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        $model->load($data, ['user_id']);
        $model->user_id = auth()->id();
        $file = UploadRepository::file($model);
        if ($file->exist()) {
            $model->size = $file->size();
        }
        if (!$model->saveIgnoreUpdate()) {
            throw new \Exception($model->getFirstError());
        }
        static::tag()->bindTag($model->id, $tags);
        UploadRepository::unzipFile($model);
        return $model;
    }

    public static function remove(int $id) {
        $model = ResourceModel::query()->where('id', $id)
            ->where('user_id', auth()->id())->first();
        if (empty($model)) {
            throw new \Exception('资源不存在');
        }
        $file = UploadRepository::file($model);
        if ($file->exist()) {
            $file->delete();
        }
        $folder = UploadRepository::resourceFolder($id);
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

}