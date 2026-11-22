<?php
declare(strict_types=1);
namespace Module\Note\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Repositories\LocalizeRepository;
use Exception;
use Module\Note\Domain\Entities\NoteEntity;
use Module\Note\Domain\Entities\TopicEntity;
use Module\Note\Domain\Model\NoteModel;

final class NoteRepository {

    const SYSTEM_TOPIC_MAX = 3;

    const STATUS_VISIBLE = 1;
    const STATUS_HIDE = 0;

    public static function getManageList(
        string $keywords = '',
        int $user = 0) {
        return NoteModel::with('user', 'topic')
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
        int $user = 0, int $id = 0, bool $notice = false, int $topic = 0, int $perPage = 20) {
        if ($topic === 0 && $notice) {
            $topic = LocalizeRepository::browserLanguageIsDefault() ? 2 : 1;
        }
        return NoteModel::with('user', 'topic')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['content']);
            })
            ->when($id > 0, function($query) use ($id) {
                $query->where('id', $id);
            })
            ->when($topic > 0, function ($query) use ($topic) {
                $query->where('topic_id', $topic);
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
        if (isset($data['is_notice']) && $data['is_notice'] > 0) {
            $data['topic_id'] = intval($data['is_notice']);
        }
        $model->load($data);
        if ($model->topic_id > 0 && $model->topic_id <= self::SYSTEM_TOPIC_MAX) {
            if (!auth()->user()->isAdministrator()) {
                throw new Exception('无权限');
            }
        } else if ($userId > 0 && $model->topic_id > 0 && !TopicEntity::where('id', $model->topic_id)
            ->where('user_id', $userId)->any()) {
            throw new Exception('无权限');
        }
        if ($userId > 0) {
            $model->user_id = $userId;
        }
        if ($id === 0 && $model->topic_id < 1 && preg_match('/^#(\S+?)#\s/', $data['content'], $match)) {
            $model->topic_id = TopicEntity::where('title', trim($match[1]))
                ->where('user_id', $userId)->value('id');
            if ($model->topic_id < 1) {
               $model->topic_id = TopicEntity::createOrThrow([
                    'title' => trim($match[1]),
                    'user_id' => $userId
               ])->id;
            }
            $model->content = trim(substr($data['content'], strlen($match[0])));
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
        NoteEntity::where('id', $id)->delete();
    }

    public static function removeSelf(int $id) {
        NoteEntity::where('id', $id)->where('user_id', auth()->id())->delete();
    }

    public static function topicList(string $keywords = '', int $user = 0) {
        return TopicEntity::with('user')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')
            ->page();
    }

    public static function topicGet(int $id) {
        $model = TopicEntity::find($id);
        if (empty($model)) {
            throw new Exception('id is error');
        }
        if ($id > self::SYSTEM_TOPIC_MAX && $model->user_id != auth()->id() && $model->status !== self::STATUS_VISIBLE) {
            throw new Exception('topic is private');
        }
        $items = NoteModel::with('user')->where('topic_id', $id)->orderBy('id', 'asc')
            ->get();
        $res = $model->toArray();
        $res['user'] = $model->user;
        $res['is_editable'] = !auth()->guest() && ($model->user_id == auth()->id() || auth()->user()->isAdministrator());
        $res['data'] = $items;
        return $res;
    }

    public static function topicSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        if ($id <= self::SYSTEM_TOPIC_MAX) {
            unset($data['title']);
        }
        $model = TopicEntity::findOrNew($id);
        if ($id > self::SYSTEM_TOPIC_MAX && $model->user_id != auth()->id()) {
            throw new Exception('note error');
        }
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function topicRemove(int $id, bool $isManage = false) {
        if ($id <= self::SYSTEM_TOPIC_MAX) {
            throw new Exception('无权限删除');
        }
        $model = TopicEntity::where('id', $id)->first();
        if (!$model || (!$isManage && $model->user_id != auth()->id())) {
            throw new Exception('无权限删除');
        }
        $model->delete();
        NoteEntity::where('topic_id', $id)->delete();
    }

    public static function topicBatchSave(array $data): void {
        $topic = intval($data['id']);
        $items = $data['items'];
        unset($data['id'], $data['items']);
        $model = TopicEntity::where('id', $topic)->first();
        if ($model->user_id != auth()->id() && !auth()->user()->isAdministrator()) {
            throw new Exception('无权限修改');
        }
        $model->load($data);
        $model->save();
        if (empty($items) || !is_array($items)) {
            return;
        }
        foreach($items as $item) {
            $id = intval($item['id']);
            if ($id === 0) {
                continue;
            }
            NoteEntity::where('topic_id', $model->id)->where('id', $id)->update([
                'properties' => $item['properties']
            ]);
        }
    }

    public static function suggestion(string $keywords) {
        return NoteModel::when(!empty($keywords), function($query) {
            SearchModel::searchWhere($query, ['content']);
        })->groupBy('content')->limit(4)->pluck('content');
    }

    public static function getNewList(int $limit) {
        return NoteModel::with('user', 'topic')->where('topic_id',
            LocalizeRepository::browserLanguageIsDefault() ? 2 : 1)
            ->orderBy('created_at', 'desc')->limit($limit ?? 5)->get();
    }

    public static function change(int $id, array $data) {
        $model = NoteEntity::findOrThrow($id);
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
