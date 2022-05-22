<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Providers\ActionLogProvider;
use Domain\Providers\CommentProvider;
use Domain\Providers\TagProvider;
use Exception;
use Module\AppStore\Domain\Models\AppModel;

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
        int $user = 0) {
        return AppModel::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })
            ->when($user > 0, function ($query) use ($user) {
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


    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = AppModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        AppModel::where('id', $id)->delete();
    }


    public static function suggestion(string $keywords) {
        return AppModel::when(!empty($keywords), function($query) {
            SearchModel::searchWhere($query, ['content']);
        })->groupBy('content')->limit(4)->pluck('content');
    }

    public static function getNewList(int $limit) {
        return AppModel::orderBy('created_at', 'desc')->limit($limit ?? 5)->get();
    }
}
