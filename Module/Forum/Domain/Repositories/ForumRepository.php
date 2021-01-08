<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Repositories;

use Module\Forum\Domain\Model\ForumClassifyModel;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ForumModeratorModel;

class ForumRepository {

    public static function getList(string $keywords = '', int $parent = 0) {
        return ForumModel::where('parent_id', $parent)
            ->when(!empty($keywords), function ($query) {
                ForumModel::searchWhere($query, 'name');
            })->page();
    }

    public static function get(int $id) {
        $model = ForumModel::findOrThrow($id, '数据有误');
        $model->classifies;
        $model->moderators;
        return $model;
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = ForumModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if (isset($data['classifies'])) {
            foreach ($data['classifies'] as $item) {
                static::saveClassify($item);
            }
        }
        if (isset($data['moderators'])) {
            static::saveModerator(array_column($data['moderators'], 'id'), $model->id);
        }
        return $model;
    }

    public static function saveModerator($users, $forum_id) {
        $exist = ForumModeratorModel::where('forum_id', $forum_id)
            ->pluck('user_id');
        $add = array_diff($users, $exist);
        $remove = array_diff($exist, $users);
        if (!empty($add)) {
            ForumModeratorModel::query()->insert(array_map(function ($user_id) use ($forum_id) {
                return compact('user_id', 'forum_id');
            }, $add));
        }
        if (!empty($remove)) {
            ForumModeratorModel::where('forum_id', $forum_id)
                ->whereIn('user_id', $remove)->delete();
        }
    }

    public static function saveClassify(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = ForumClassifyModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        ForumModel::where('id', $id)->delete();
    }

    public static function all() {
        return ForumModel::tree()->makeTreeForHtml();
    }
}