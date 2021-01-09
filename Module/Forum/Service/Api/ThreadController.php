<?php
namespace Module\Forum\Service\Api;

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

    public function indexAction($forum, $classify = 0) {
        $items = ThreadModel::with('user', 'classify')
            ->when($classify > 0, function ($query) use ($classify) {
                $query->where('classify_id', intval($classify));
            })->whereIn('forum_id', ForumModel::getAllChildrenId($forum))
            ->orderBy('id', 'desc')->page();
        return $this->renderPage($items);
    }

    public function postAction(int $thread, int $user = 0) {
        return $this->renderPage(
            ThreadRepository::postList($thread, $user)
        );
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
        $model = ThreadPostModel::create([
            'content' => $content,
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'grade' => 0,
            'ip' => request()->ip()
        ]);
        ForumModel::updateCount($thread->forum_id, 'thread_count');
        return $this->render($model);
    }

    public function detailAction($id) {
        $thread = ThreadModel::find($id);
        $thread->forum;
        $thread->path = array_merge(ForumModel::findPath($thread->forum_id), [$thread->forum]);
        $thread->digestable = $thread->canDigest();
        $thread->highlightable = $thread->canHighlight();
        $thread->closeable = $thread->canClose();
        return $this->render($thread);
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
        return $this->render($post);
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
        return $this->render($post);
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
        return $this->render($thread);
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
        return $this->render($thread);
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
        return $this->render($thread);
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
        return $this->renderData(true);
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