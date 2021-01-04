<?php
namespace Module\Forum\Service;


use Module\Forum\Domain\Error\StopNextException;
use Module\Forum\Domain\Model\ForumClassifyModel;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;
use Module\Forum\Domain\Parsers\Parser;
use Module\Forum\Domain\Repositories\ThreadRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class ThreadController extends Controller {

    public function rules() {
        return [
            'create' => '@',
            'edit' => '@',
            'edit_post' => '@',
            'reply' => '@',
            'digest' => '@',
            'highlight' => '@',
            'do' => '@',
            '*' => '*'
        ];
    }

    public function indexAction($id, $user = 0, $page = 1) {
        if ($page < 2 && $user < 1) {
            ThreadModel::query()->where('id', $id)
                ->updateOne('view_count');
        }
        $thread = ThreadModel::find($id);
        if (empty($thread)) {
            return $this->redirectWithMessage('./');
        }
        $forum = ForumModel::findById($thread->forum_id);
        $path = ForumModel::findPath($thread->forum_id);
        $path[] = $forum;
        $post_list = ThreadPostModel::with('user')
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->where('thread_id', $id)
            ->orderBy('grade', 'asc')
            ->orderBy('created_at', 'asc')->page();
        return $this->show(compact('thread', 'path', 'post_list'));
    }

    public function createAction($title, $content,
                                 $forum_id, $classify_id = 0, $is_private_post = 0) {
        if (empty($title)) {
            return $this->renderFailure('标题不能为空');
        }
        $forum_id = intval($forum_id);
        if ($forum_id < 1) {
            return $this->renderFailure('请选择版块');
        }
        $thread = ThreadModel::create([
            'title' => $title,
            'forum_id' => $forum_id,
            'classify_id' => intval($classify_id),
            'user_id' => auth()->id(),
            'is_private_post' => $is_private_post
        ]);
        if (empty($thread)) {
            return $this->renderFailure('发帖失败');
        }
        ThreadPostModel::create([
            'content' => $content,
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'grade' => 0,
            'ip' => request()->ip()
        ]);
        ForumModel::updateCount($thread->forum_id, 'thread_count');
        return $this->renderData([
            'url' => url('./forum', ['id' => $forum_id])
        ]);
    }

    public function editAction($id) {
        $thread = ThreadModel::find($id);
        if ($thread->user_id !== auth()->id()) {
            return $this->redirect('./');
        }
        $post = ThreadPostModel::where([
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'grade' => 0,
        ])->first();
        $classify_list = ForumClassifyModel::where('forum_id', $thread->forum_id)
            ->orderBy('id', 'asc')->all();
        return $this->show(compact('thread', 'post', 'classify_list'));
    }

    public function updateAction($id, Request $request) {
        $thread = ThreadModel::find($id);
        if ($thread->user_id !== auth()->id()) {
            return $this->renderFailure('无权限');
        }
        if ($request->has('title')) {
            $thread->title = $request->get('title');
        }
        if ($request->has('classify_id')) {
            $thread->classify_id = $request->get('classify_id');
        }
        $thread->save();
        $post = ThreadPostModel::where([
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'grade' => 0,
        ])->update([
            'content' => $request->get('content')
        ]);
        return $this->renderData([
            'url' => url('./thread', ['id' => $id])
        ], '更新成功');
    }

    public function replyAction($content, $thread_id) {
        if (empty($content)) {
            return $this->renderFailure('请输入内容');
        }
        $thread_id = intval($thread_id);
        if ($thread_id < 1) {
            return $this->renderFailure('请选择帖子');
        }
        $thread = ThreadModel::find($thread_id);
        if (empty($thread)) {
            return $this->renderFailure('请选择帖子');
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
            return $this->renderFailure('发表失败');
        }
        ForumModel::updateCount($thread->forum_id, 'post_count');
        ThreadModel::query()->where('id', $thread_id)
            ->updateOne('post_count');
        return $this->renderData([
            'url' => url('./thread', ['id' => $thread_id, 'page' => ceil($post->grade / 20)])
        ]);
    }

    public function digestAction($id) {
        $thread = ThreadModel::find($id);
        if (empty($thread)) {
            return $this->renderFailure('请选择帖子');
        }
        if (!$thread->canDigest()) {
            return $this->renderFailure('无权限');
        }
        ThreadModel::query()->where('id', $id)
            ->updateBool('is_digest');
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function highlightAction($id) {
        $thread = ThreadModel::find($id);
        if (empty($thread)) {
            return $this->renderFailure('请选择帖子');
        }
        if (!$thread->canHighlight()) {
            return $this->renderFailure('无权限');
        }
        ThreadModel::query()->where('id', $id)
            ->updateBool('is_highlight');
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function closeAction($id) {
        $thread = ThreadModel::find($id);
        if (empty($thread)) {
            return $this->renderFailure('请选择帖子');
        }
        if (!$thread->canClose()) {
            return $this->renderFailure('无权限');
        }
        ThreadModel::query()->where('id', $id)
            ->updateBool('is_closed');
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function removePostAction($id) {
        $item = ThreadPostModel::find($id);
        if (empty($item)) {
            return $this->renderFailure('请选择回帖');
        }
        $thread = ThreadModel::find($item->thread_id);
        if (empty($thread)) {
            return $this->renderFailure('请选择帖子');
        }
        if (!$thread->canRemovePost($item)) {
            return $this->renderFailure('无权限');
        }
        $item->delete();
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function collectAction($id) {
        try {
            $res = ThreadRepository::toggleCollect($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($res);
    }

    public function agreeAction($id) {
        try {
            $res = ThreadRepository::agreePost($id, true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($res);
    }

    public function disagreeAction($id) {
        try {
            $res = ThreadRepository::agreePost($id, false);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($res);
    }

    public function doAction(Request $request, $id) {
        try {
            $model = ThreadPostModel::find($id);
            $html = Parser::converterWithRequest($model, $request);
        } catch (StopNextException $ex) {
            return app('response');
        }
        catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'id' => $id,
            'content' => $html
        ]);
    }
}