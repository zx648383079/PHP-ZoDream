<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Auth\Domain\FundAccount;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\Forum\Domain\Model\ForumLogModel;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadLogModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;
use Module\Forum\Domain\Model\ThreadSimpleModel;
use Module\Forum\Domain\Parsers\Parser;
use Zodream\Helpers\Json;
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

    /**
     * @param int $id
     * @return ThreadModel
     * @throws Exception
     */
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
                                   int $classify = 0, string $keywords = '',
                                   int $user = 0, int $type = 0, string $sort = '', string $order = '') {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, [
            'updated_at', 'created_at', 'post_count', 'top_type'
        ]);
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
            ->orderBy($sort, $order)->page();
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
//            ->where('grade', 0)
            ->orderBy('id', 'desc')
            ->first('id', 'user_id', 'created_at');
    }

    public static function postList(
        int $thread_id, int $user_id = 0, int $post_id = 0, int $status = 0, string $sort = '', string $order = '',
        int $per_page = 20, string $type = '') {
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
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, [
            'grade', 'status'
        ], 'asc');
        /** @var Page<ThreadPostModel> $items */
        $items = ThreadPostModel::with('user', 'thread')
            ->when($user_id > 0, function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->where('thread_id', $thread_id)
            ->orderBy($sort, $order)
            ->orderBy('created_at', 'asc')
            ->page($per_page, 'page', $page);
        $data = $items->getPage();
        foreach ($data as $item) {
            $item->is_public_post = $item->getIsPublicPostAttribute();
            $item->content = $item->is_public_post ? Parser::create($item, request())
                ->render($type) : '';
            $item->deleteable = static::canRemovePost($item->thread, $item);
        }
        usort($data, function ($a, $b) {
            if ($a['grade'] < 1 || $b['grade'] < 1) {
                return $a['grade'] > $b['grade'] ? 1 : -1;
            }
            return 0;
        });
        return $items->setPage($data);
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
        return static::toggleCollectThread($model);
    }

    public static function toggleCollectThread(ThreadModel $model) {
        $yes = LogRepository::toggleAction($model->id, ThreadLogModel::TYPE_THREAD, ThreadLogModel::ACTION_COLLECT);
        $model->collect_count += ($yes ? 1 : -1);
        $model->save();
        return $yes;
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
        $action = $agree ? ThreadLogModel::ACTION_AGREE :ThreadLogModel::ACTION_DISAGREE;
        $res = LogRepository::toggleLog(ThreadLogModel::TYPE_THREAD_POST,
            $action,
            $model->id,
            [ThreadLogModel::ACTION_AGREE, ThreadLogModel::ACTION_DISAGREE]);
        if ($res < 1) {
            if ($action === ThreadLogModel::ACTION_AGREE) {
                $model->agree_count --;
            } else {
                $model->disagree_count --;
            }
        } elseif ($res === 1) {
            $plus = $action === ThreadLogModel::ACTION_AGREE ? 1 : -1;
            $model->agree_count += $plus;
            $model->disagree_count -= $plus;
        } else {
            if ($action === ThreadLogModel::ACTION_AGREE) {
                $model->agree_count ++;
            } else {
                $model->disagree_count ++;
            }
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


    public static function getFull(int $id, bool $isSee = false) {
        if ($isSee) {
            ThreadModel::query()->where('id', $id)
                ->updateIncrement('view_count');
        }
        $model = static::get($id);
        $model->forum;
        $model->path = array_merge(ForumModel::findPath($model->forum_id), [$model->forum]);
        $model->digestable = static::can($model, 'is_digest');
        $model->highlightable = static::can($model, 'is_highlight');
        $model->closeable = static::can($model, 'is_closed');
        $model->topable = static::can($model, 'top_type');
        $model->editable = static::editable($model);
        $model->classify;
        $model->last_post = static::lastPost($model->id, false);
        $model->is_new = static::isNew($model);
        $model->like_type = LogRepository::userActionValue($id, ThreadLogModel::TYPE_THREAD,
            [ThreadLogModel::ACTION_AGREE, ThreadLogModel::ACTION_DISAGREE]);
        $model->is_collected = LogRepository::userAction($id, ThreadLogModel::TYPE_THREAD,
            ThreadLogModel::ACTION_COLLECT);
        $model->is_reward = LogRepository::userAction($id, ThreadLogModel::TYPE_THREAD,
            ThreadLogModel::ACTION_REWARD);
        $model->reward_count = ThreadLogModel::where('item_type', ThreadLogModel::TYPE_THREAD)
            ->where('item_id', $id)
            ->where('action', ThreadLogModel::ACTION_REWARD)->count();
        $model->reward_items = ThreadLogModel::with('user')
            ->where('item_type', ThreadLogModel::TYPE_THREAD)
            ->where('item_id', $id)
            ->where('action', ThreadLogModel::ACTION_REWARD)->orderBy('id', 'desc')
            ->limit(5)->get();
        return $model;
    }

    public static function editable(ThreadModel $model): bool {
        if (auth()->guest() || $model->is_closed > 0) {
            return false;
        }
        return $model->user_id === auth()->id() || static::can($model, 'edit');
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
            ->update([
                'post_count=post_count+1',
                'updated_at' => time()
            ]);
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
        if (in_array('like', $data) || array_key_exists('like', $data)) {
            $thread->like_type = static::toggleLike($thread);
            return $thread;
        }
        if (in_array('collect', $data) || array_key_exists('collect', $data)) {
            $thread->is_collected = static::toggleCollectThread($thread);
            return $thread;
        }
        if (isset($data['reward'])) {
            static::rewardThread($thread, floatval($data['reward']));
            $thread->is_reward = true;
            return $thread;
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
        ForumLogModel::create([
            'item_type' => ForumLogModel::TYPE_THREAD,
            'item_id' => $thread->id,
            'action' => 1,
            'data' => Json::encode($data)
        ]);
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
        ForumLogModel::create([
            'item_type' => ForumLogModel::TYPE_THREAD,
            'item_id' => $id,
            'action' => ForumLogModel::ACTION_DELETE,
        ]);
    }


    public static function suggestion(string $keywords = '') {
        return ThreadSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'title');
        })->limit(4)->get();
    }

    public static function rewardList(int $item_id, int $item_type = 0) {
        return ThreadLogModel::with('user')
            ->where('item_type', $item_type)
            ->where('item_id', $item_id)
            ->where('action', ThreadLogModel::ACTION_REWARD)->orderBy('id', 'desc')
            ->page();
    }

    private static function toggleLike(ThreadModel $thread)
    {
        return LogRepository::changeAction($thread->id, ThreadLogModel::TYPE_THREAD, ThreadLogModel::ACTION_AGREE, [
            ThreadLogModel::ACTION_AGREE, ThreadLogModel::ACTION_DISAGREE
        ]);
    }

    private static function rewardThread(ThreadModel $thread, float $money)
    {
        if ($thread->user_id == auth()->id()) {
            throw new \Exception('不能自己打赏自己');
        }
        $res = FundAccount::payTo(auth()->id(),
            FundAccount::TYPE_FORUM_BUY, function () use ($thread) {
            return ThreadLogModel::create([
                'item_type' => ThreadLogModel::TYPE_THREAD,
                'item_id' => $thread->id,
                'action' => ThreadLogModel::ACTION_REWARD,
                'user_id' => auth()->id(),
            ]);
        }, -$money, sprintf('打赏帖子《%s》', $thread->title), $thread->user_id);
        if (!$res) {
            throw new \Exception('支付失败，请检查您的账户余额');
        }
    }

    public static function changePost(int $id, int $status) {
        $model = ThreadPostModel::findOrThrow($id, '操作有无');
        $thread = ThreadModel::findOrThrow($model->thread_id, '帖子不存在');
        if (!static::editable($thread)) {
            throw new Exception('无权限操作');
        }
        $model->status = $status;
        $model->save();
        ForumLogModel::create([
            'item_type' => ForumLogModel::TYPE_POST,
            'item_id' => $id,
            'action' => ForumLogModel::ACTION_STATUS,
            'data' => $status,
        ]);
        return $model;
    }

    /**
     * 获取用户的统计信息
     * @param int $id
     * @return array
     */
    public static function getUser(int $id): array {
        $user = UserRepository::getPublicProfile($id);
        $user['count_items'] = [
            ['name' => '主题', 'count' => ThreadModel::where('user_id', $id)->count()],
            ['name' => '帖子', 'count' => ThreadPostModel::where('user_id', $id)->count()],
            ['name' => '积分', 'count' => 0],
        ];
        $user['medal_items'] = [
            // ['name' => '', 'icon' => url()->asset('')],
        ];
        return $user;
    }
}