<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadLogModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;
use Module\Forum\Domain\Model\ThreadSimpleModel;
use Module\Forum\Domain\Parsers\Parser;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Html\Page;

class ThreadRepository {

    public static function manageList(string $keywords = '', int $forum_id = 0) {
        return ThreadModel::with('user', 'forum')->when(!empty($forum_id), function ($query) use ($forum_id) {
                $query->where('forum_id', $forum_id);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->page();
    }

    public static function get(int $id) {
        return ThreadModel::findOrThrow($id, '数据有误');
    }

    public static function getSource(int $id) {
        $model = static::get($id);
        if ($model->user_id !== auth()->id()) {
            throw new Exception('操作失败');
        }
        $model->content = ThreadPostModel::where([
            'user_id' => auth()->id(),
            'thread_id' => $model->id,
            'grade' => 0,
        ])->value('content');
        return $model;
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ThreadModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function manageRemove(int $id) {
        ThreadModel::where('id', $id)->delete();
        ThreadPostModel::where('thread_id', $id)->delete();
    }

    public static function getList(int $forum,
                                   int $classify = 0, string $keywords = '', int $user = 0, int $type = 0) {
        $data = ThreadModel::with('user', 'classify')
            ->when($classify > 0, function ($query) use ($classify) {
                $query->where('classify_id', $classify);
            })->whereIn('forum_id', ForumModel::getAllChildrenId($forum))
            ->when(!empty($keywords), function ($query) use ($type, $keywords) {
                if ($type < 2) {
                    SearchModel::searchWhere($query, 'title');
                    return;
                }
                $userId = UserRepository::searchUserId($keywords);
                if (empty($userId)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('user_id', $userId);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->orderBy('id', 'desc')->page();
        foreach ($data as $item) {
            $item->last_post = static::lastPost($item->id);
            $item->is_new = static::isNew($item);
        }
        return $data;
    }

    public static function topList(int $forum) {
        $data = ThreadModel::with('user', 'classify')
            ->where('forum_id', $forum)
            ->where('top_type', '>', 0)
            ->orderBy('top_type', 'desc')
            ->orderBy('id', 'desc')->get();
        foreach ($data as $item) {
            $item->last_post = static::lastPost($item->id);
            $item->is_new = static::isNew($item);
        }
        return $data;
    }

    protected static function isNew(ThreadModel $model): bool {
        $time = $model->getAttributeSource('created_at');
        if ($model->last_post) {
            $time = $model->last_post->getAttributeSource('created_at');
        }
        return $time > time() - 86400;
    }

    protected static function lastPost(int $thread, bool $hasUser = true) {
        $query = $hasUser ? ThreadPostModel::with('user') : ThreadPostModel::query();
        return $query
            ->where('thread_id', $thread)
            ->where('grade', 0)
            ->orderBy('id', 'desc')->first('id', 'user_id', 'created_at');
    }

    public static function postList(
        int $thread_id, int $user_id = 0, int $post_id = 0, int $per_page = 20, string $type = '') {
        $page = -1;
        if ($post_id > 0) {
            $maps = ThreadPostModel::when($user_id > 0, function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })
                ->where('thread_id', $thread_id)
                ->orderBy('grade', 'asc')
                ->orderBy('created_at', 'asc')->pluck('id');
            $count = empty($maps) ? false : array_search($post_id, $maps);
            if ($count === false) {
                throw new Exception('找不到该回帖');
            }
            $page = (int)ceil(($count + 1) / $per_page);
        }
        $items = ThreadPostModel::with('user', 'thread')
            ->when($user_id > 0, function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->where('thread_id', $thread_id)
            ->orderBy('grade', 'asc')
            ->orderBy('created_at', 'asc')->page($per_page, 'page', $page);
        foreach ($items as $item) {
            $item->is_public_post = $item->getIsPublicPostAttribute();
            $item->content = $item->is_public_post ? Parser::create($item, request())
                ->render($type) : '';
            $item->deleteable = static::canRemovePost($item->thread, $item);
        }
        return $items;
    }

    /**
     * 收藏
     * @param $id
     * @return bool
     * @throws Exception
     */
    public static function toggleCollect(int $id) {
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
    public static function agreePost(int $id, bool $agree = true) {
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

    public static function create(string $title, string $content,
                                 int $forum_id, int $classify_id = 0, int $is_private_post = 0) {
        if (empty($title)) {
            throw new Exception('标题不能为空');
        }
        if ($forum_id < 1) {
            throw new Exception('请选择版块');
        }
        $thread = ThreadModel::create([
            'title' => $title,
            'forum_id' => $forum_id,
            'classify_id' => $classify_id,
            'user_id' => auth()->id(),
            'is_private_post' => $is_private_post
        ]);
        if (empty($thread)) {
            throw new Exception('发帖失败');
        }
        $model = ThreadPostModel::create([
            'content' => $content,
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'grade' => 0,
            'ip' => request()->ip()
        ]);
        ForumRepository::updateCount($thread->forum_id, 'thread_count');
        return $model;
    }

    public static function update(int $id, array $data) {
        $thread = static::get($id);
        if ($thread->user_id !== auth()->id()) {
            throw new Exception('无权限');
        }
        if ($thread->is_closed) {
            throw new Exception('帖子已锁定，无法编辑');
        }
        if (isset($data['title'])) {
            $thread->title = $data['title'];
        }
        if (array_key_exists('classify_id', $data)) {
            $thread->classify_id = intval($data['classify_id']);
        }
        if (array_key_exists('is_private_post', $data)) {
            $thread->is_private_post = intval($data['is_private_post']);
        }
        $thread->save();
        ThreadPostModel::where([
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'grade' => 0,
        ])->update([
            'content' => $data['content']
        ]);
        return $thread;
    }


    public static function getFull(int $id) {
        $model = static::get($id);
        $model->forum;
        $model->path = array_merge(ForumModel::findPath($model->forum_id), [$model->forum]);
        $model->digestable = static::can($model, 'is_digest');
        $model->highlightable = static::can($model, 'is_highlight');
        $model->closeable = static::can($model, 'is_closed');
        $model->topable = static::can($model, 'top_type');
        $model->classify;
        $model->last_post = static::lastPost($model->id, false);
        $model->is_new = static::isNew($model);
        return $model;
    }


    public static function reply(string $content, int $thread_id) {
        if (empty($content)) {
            throw new Exception('请输入内容');
        }
        if ($thread_id < 1) {
            throw new Exception('请选择帖子');
        }
        $thread = static::get($thread_id);
        if ($thread->is_closed) {
            throw new Exception('帖子已关闭');
        }
        $max = ThreadPostModel::where('thread_id', $thread_id)->max('grade');
        $post = ThreadPostModel::create([
            'content' => $content,
            'user_id' => auth()->id(),
            'thread_id' => $thread_id,
            'grade' => intval($max) + 1,
            'ip' => request()->ip()
        ]);
        if (empty($post)) {
            throw new Exception('发表失败');
        }
        ForumRepository::updateCount($thread->forum_id, 'post_count');
        ThreadModel::query()->where('id', $thread_id)
            ->updateIncrement('post_count');
        return $post;
    }

    /**
     * 操作主题
     * @param int $id
     * @return ThreadModel
     * @throws Exception
     */
    public static function threadAction(int $id, array $data) {
        $thread = static::get($id);
        if (empty($thread)) {
            throw new Exception('请选择帖子');
        }
        $maps = ['is_highlight', 'is_digest', 'is_closed', 'top_type'];
        foreach ($data as $action => $val) {
            if (is_int($action)) {
                if (empty($val)) {
                    continue;
                }
                list($action, $val) = [$val, $thread->{$val} > 0 ? 0 : 1];
            }
            if (empty($action) || !in_array($action, $maps)) {
                continue;
            }
            if (!static::can($thread, $action)) {
                throw new Exception('无权限');
            }
            $thread->{$action} = intval($val);
        }
        $thread->save();
        return $thread;
    }

    /**
     * 是否有权限执行操作
     * @param ThreadModel $model
     * @param string $action
     * @return bool
     * @throws Exception
     */
    public static function can(ThreadModel $model, string $action): bool {
        if (auth()->guest()) {
            return false;
        }
        return auth()->user()->hasRole('administrator');
    }

    public static function canRemovePost(ThreadModel $model, ThreadPostModel $item): bool {
        if (auth()->guest()) {
            return false;
        }
        if (auth()->id() == $model->user_id) {
            return true;
        }
        if (auth()->id() == $item->user_id) {
            return true;
        }
        return auth()->user()->hasRole('administrator');
    }

    public static function removePost(int $id) {
        $item = ThreadPostModel::find($id);
        if (empty($item)) {
            throw new Exception('请选择回帖');
        }
        $thread = static::get($item->thread_id);
        if (!static::canRemovePost($thread, $item)) {
            throw new Exception('无权限');
        }
        $item->delete();
        ForumRepository::updateCount($thread->forum_id, 'post_count', -1);
        ThreadModel::query()->where('id', $thread->id)
            ->updateDecrement('post_count');
    }

    public static function remove(int $id) {
        $thread = static::get($id);
        if ($thread->user_id !== auth()->id() && static::can($thread, 'delete')) {
            throw new Exception('操作失败');
        }
        $thread->delete();
        $count = ThreadPostModel::where('thread_id', $id)
            ->count() - 1;
        ThreadPostModel::where('thread_id', $id)->delete();
        ForumRepository::updateCount($thread->forum_id, 'thread_count', -1);
        ForumRepository::updateCount($thread->forum_id, 'post_count', - $count);
    }


    public static function suggestion(string $keywords = '') {
        return ThreadSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'title');
        })->limit(4)->get();
    }
}