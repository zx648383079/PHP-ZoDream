<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Repositories\ZoneRepository;
use Module\Forum\Domain\Model\ForumClassifyModel;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ForumModeratorModel;
use Module\Forum\Domain\Model\ThreadModel;
use Zodream\Helpers\Tree as TreeHelper;

final class ForumRepository {

    public static function getList(string $keywords = '', int $parent = 0) {
        return ForumModel::where('parent_id', $parent)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->page();
    }

    public static function get(int $id, bool $full = true) {
        $model = ForumModel::findOrThrow($id, '数据有误');
        if ($full) {
            $model->classifies = ForumClassifyModel::where('forum_id', $id)
                ->orderBy('id', 'asc')->get();
            $model->moderators;
        }
        return $model;
    }

    public static function getFull(int $id, bool $full = true) {
        $model = ForumModel::findOrThrow($id, '数据有误');
        $model->classifies = ForumClassifyModel::where('forum_id', $id)
            ->orderBy('id', 'asc')->get();
        if ($full) {
            $model->moderators;
            $model->children = static::children($id, false);
            $model->path = ForumModel::findPath($id);
            $model->thread_top = ThreadRepository::topList($id);
        }
        return $model;
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ForumModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if (isset($data['classifies'])) {
            foreach ($data['classifies'] as $item) {
                $item['forum_id'] = $model->id;
                static::saveClassify($item);
            }
        }
        if (isset($data['moderators'])) {
            static::saveModerator(array_column($data['moderators'], 'id'), $model->id);
        }
        return $model;
    }

    public static function saveModerator(array $users, int $forum_id) {
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
        $id = $data['id'] ?? 0;
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

    public static function children(int $id, bool $hasChildren = true) {
        $query = $hasChildren ? ForumModel::with('children') : ForumModel::query();
        $data = $query
            ->where('parent_id', $id)->get();
        $zoneId = auth()->guest() ? 0 : ZoneRepository::getId(auth()->id());
        foreach ($data as $item) {
            $item->last_thread = static::lastThread($item['id'], $zoneId);
            $item->today_count = self::getTodayCount($item);
            if ($hasChildren) {
                foreach ($item->children as $it) {
                    $it->today_count = self::getTodayCount($it);
                    $it->last_thread = static::lastThread($it['id'], $zoneId);
                }
            }
        };
        return $data;
    }

    public static function getTodayCount(ForumModel $model): int {
        if ($model->thread_count < 1) {
	        return 0;
        }
	    $time = strtotime(date('Y-m-d'));
	    return ThreadModel::where('forum_id', $model->id)
            ->where('created_at', '>=',  $time)
            ->where('created_at', '<=',  $time + 86400)->count();
    }

    private static function lastThread(int $id, int $zoneId) {
        return ThreadModel::with('user')
            ->where('zone_id', $zoneId)
            ->where('forum_id', $id)
            ->where('status', ThreadRepository::REVIEW_STATUS_APPROVED)
            ->orderBy('id', 'desc')->first('id', 'title', 'user_id', 'view_count',
            'post_count',
            'collect_count', 'updated_at');
    }

    public static function updateCount(int $id, string $key = 'thread_count', int $count = 1) {
        $path = TreeHelper::getTreeParent(ForumModel::cacheAll(), $id);
        $path[] = $id;
        return ForumModel::whereIn('id', $path)
            ->updateIncrement($key, $count);
    }
}