<?php
declare(strict_types=1);
namespace Module\Note\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Note\Domain\Model\NoteModel;

final class NoteRepository {

    const STATUS_VISIBLE = 1;
    const STATUS_HIDE = 0;

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
        int $user = 0, int $id = 0, bool $notice = false, int $perPage = 20) {
        return NoteModel::with('user')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['content']);
            })
            ->when($id > 0, function($query) use ($id) {
                $query->where('id', $id);
            })
            ->when($notice, function ($query) {
                $query->where('is_notice', 1);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->where(function ($query) {
                $query->where('status', self::STATUS_VISIBLE);
                if (auth()->guest()) {
                    return;
                }
                $query->orWhere(function ($query) {
                    $query->where('user_id', auth()->id())
                    ->where('status', self::STATUS_HIDE);
                });
            })->orderBy('created_at', 'desc')
            ->page($perPage);
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
        return NoteModel::with('user')->where('is_notice', 1)
            ->orderBy('created_at', 'desc')->limit($limit ?? 5)->get();
    }

    public static function change(int $id, array $data) {
        $model = NoteModel::findOrThrow($id);
        $maps = ['is_notice'];
        foreach ($data as $action => $val) {
            if (is_int($action)) {
                if (empty($val)) {
                    continue;
                }
                list($action, $val) = [$val, $model->{$val} > 0 ? 0 : 1];
            }
            if (empty($action) || !in_array($action, $maps)) {
                continue;
            }
            $model->{$action} = intval($val);
        }
        $model->save();
        return $model;
    }
}
