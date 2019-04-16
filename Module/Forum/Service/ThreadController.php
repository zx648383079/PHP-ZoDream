<?php
namespace Module\Forum\Service;


use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;

class ThreadController extends Controller {

    public function indexAction($id) {
        $thread = ThreadModel::find($id);
        if (empty($thread)) {
            return $this->redirectWithMessage('./');
        }
        $forum = ForumModel::findById($thread->forum_id);
        $path = ForumModel::findPath($thread->forum_id);
        $path[] = $forum;
        $post_list = ThreadPostModel::with('user')->where('thread_id', $id)
            ->orderBy('grade', 'asc')
            ->orderBy('created_at', 'asc')->page();
        return $this->show(compact('thread', 'path', 'post_list'));
    }

    public function createAction($title, $content, $forum_id, $classify_id = 0) {
        if (empty($title)) {
            return $this->jsonFailure('标题不能为空');
        }
        $forum_id = intval($forum_id);
        if ($forum_id < 1) {
            return $this->jsonFailure('请选择版块');
        }
        $thread = ThreadModel::create([
            'title' => $title,
            'forum_id' => $forum_id,
            'classify_id' => intval($classify_id),
            'user_id' => auth()->id(),
        ]);
        if (empty($thread)) {
            return $this->jsonFailure('发帖失败');
        }
        ThreadPostModel::create([
            'content' => $content,
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'grade' => 0,
            'ip' => app('request')->ip()
        ]);
        return $this->jsonSuccess([
            'url' => url('./forum', ['id' => $forum_id])
        ]);
    }

    public function replyAction($content, $thread_id) {
        if (empty($content)) {
            return $this->jsonFailure('请输入内容');
        }
        $thread_id = intval($thread_id);
        if ($thread_id < 1) {
            return $this->jsonFailure('请选择帖子');
        }
        $max = ThreadPostModel::where('thread_id', $thread_id)->max('grade');
        $post = ThreadPostModel::create([
            'content' => $content,
            'user_id' => auth()->id(),
            'thread_id' => $thread_id,
            'grade' => intval($max) + 1,
            'ip' => app('request')->ip()
        ]);
        if (empty($post)) {
            return $this->jsonFailure('发表失败');
        }
        return $this->jsonSuccess([
            'url' => url('./thread', ['id' => $thread_id, 'page' => ceil($post->grade / 20)])
        ]);
    }
}