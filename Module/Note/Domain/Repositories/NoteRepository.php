<?php
declare(strict_types=1);
namespace Module\Note\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Note\Domain\Model\NoteModel;

final class NoteRepository {

    public static function getManageList(
        string $keywords = '',
        int $user = 0) {
        return NoteModel::with('user')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['content']);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')
            ->page();
    }

    public static function getList(
        string $keywords = '',
        int $user = 0, int $id = 0) {
        return NoteModel::with('user')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['content']);
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
        $model = NoteModel::with('user')->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('note error');
        }
        return $model;
    }

    public static function getSelf(int $id) {
        $model = NoteModel::with('user')->where('id', $id)->where('user_id', auth()->id())->first();
        if (empty($model)) {
            throw new Exception('note error');
        }
        return $model;
    }

    public static function save(array $data, int $userId = 0) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = NoteModel::findOrNew($id);
        if ($id > 0 && $userId > 0 && $model->user_id != $userId) {
            throw new Exception('note error');
        }
        $model->load($data);
        if ($userId > 0) {
            $model->user_id = $userId;
        }
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function saveSelf(array $data) {
        return static::save($data, auth()->id());
    }

    public static function remove(int $id) {
        NoteModel::where('id', $id)->delete();
    }

    public static function removeSelf(int $id) {
        NoteModel::where('id', $id)->where('user_id', auth()->id())->delete();
    }

    public static function suggestion(string $keywords) {
        return NoteModel::when(!empty($keywords), function($query) {
            SearchModel::searchWhere($query, ['content']);
        })->groupBy('content')->limit(4)->pluck('content');
    }

    public static function getNewList(int $limit) {
        return NoteModel::with('user')->orderBy('created_at', 'desc')->limit($limit ?? 5)->get();
    }
}
