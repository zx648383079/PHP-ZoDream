<?php
namespace Module\Forum\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Forum\Domain\Model\ThreadLogModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;
use Module\Forum\Domain\Parsers\Parser;

class ThreadRepository {

    public static function getList(string $keywords = '', int $forum_id = 0) {
        return ThreadModel::with('user', 'forum')->when(!empty($forum_id), function ($query) use ($forum_id) {
                $query->where('forum_id', intval($forum_id));
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->page();
    }

    public static function get(int $id) {
        return ThreadModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = ThreadModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        ThreadModel::where('id', $id)->delete();
        ThreadPostModel::where('thread_id', $id)->delete();
    }

    public static function postList(int $thread_id, int $user_id = 0) {
        $items = ThreadPostModel::with('user', 'thread')
            ->when($user_id > 0, function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->where('thread_id', $thread_id)
            ->orderBy('grade', 'asc')
            ->orderBy('created_at', 'asc')->page();
        foreach ($items as $item) {
            $item->content = Parser::converter($item);
            $item->deleteable = $item->thread->canRemovePost($item);
        }
        return $items;
    }

    /**
     * 收藏
     * @param $id
     * @return bool
     * @throws Exception
     */
    public static function toggleCollect($id) {
        $model = ThreadModel::find($id);
        if (empty($model)) {
            throw new Exception('帖子不存在');
        }
        $count = ThreadLogModel::query()->where('item_type', ThreadLogModel::TYPE_THREAD)
            ->where('item_id', $model->id)
            ->where('user_id', auth()->id())
            ->where('action', ThreadLogModel::ACTION_COLLECT)
            ->count();
        if ($count > 0) {
            ThreadLogModel::query()->where('item_type', ThreadLogModel::TYPE_THREAD)
                ->where('item_id', $model->id)
                ->where('user_id', auth()->id())
                ->where('action', ThreadLogModel::ACTION_COLLECT)->delete();
            $model->collect_count -= $count;
            $model->save();
            return false;
        }
        $log = ThreadLogModel::create([
            'item_type' => ThreadLogModel::TYPE_THREAD,
            'item_id' => $model->id,
            'user_id' => auth()->id(),
            'action' => ThreadLogModel::ACTION_COLLECT,
        ]);
        if (!$log) {
            throw new Exception('保存未成功');
        }
        $model->collect_count ++;
        $model->save();
        return true;
    }

    /**
     * 是否同意回帖内容
     * @param $id
     * @param bool $agree
     * @return array
     * @throws Exception
     */
    public static function agreePost($id, bool $agree = true) {
        $model = ThreadPostModel::find($id);
        if (empty($model)) {
            throw new Exception('回复不存在');
        }
        $action = $agree ? ThreadLogModel::ACTION_AGREE : ThreadLogModel::ACTION_DISAGREE;
        /** @var ThreadLogModel $log */
        $log = ThreadLogModel::query()
            ->where('item_type', ThreadLogModel::TYPE_THREAD_POST)
            ->where('item_id', $model->id)
            ->where('user_id', auth()->id())
            ->whereIn('action',
                [ThreadLogModel::ACTION_AGREE, ThreadLogModel::ACTION_DISAGREE])
            ->first();
        if ($log && $log->action == $action) {
            throw new Exception('请勿重复点击');
        }
        if (empty($log)) {
            $log = ThreadLogModel::create([
                'item_type' => ThreadLogModel::TYPE_THREAD_POST,
                'item_id' => $model->id,
                'user_id' => auth()->id(),
                'action' => $action,
            ]);
        } else {
            $log->action = $action;
            $log->created_at = time();
            $log->save();
            if ($agree) {
                $model->disagree_count --;
            } else {
                $model->agree_count --;
            }
            $model->save();
        }
        if (!$log) {
            throw new Exception('操作失败');
        }
        if ($agree) {
            $model->agree_count ++;
        } else {
            $model->disagree_count ++;
        }
        $model->save();
        return [
          'is_agree' => $agree,
          'is_disagree' => !$agree,
          'agree_count' => $model->agree_count,
          'disagree_count' => $model->disagree_count
        ];
    }
}