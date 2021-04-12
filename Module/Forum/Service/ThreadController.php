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

    public function indexAction(int $id, int $user = 0, int $page = 1) {
        if ($page < 2 && $user < 1) {
            ThreadModel::query()->where('id', $id)
                ->updateIncrement('view_count');
        }
        try {
            $thread = ThreadRepository::getFull($id);
            $path = $thread->path;
            $post_list = ThreadRepository::postList($id, $user);
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('./', $ex->getMessage());
        }
        return $this->show(compact('thread', 'path', 'post_list'));
    }

    public function createAction(string $title, string $content,
                                 int $forum_id, int $classify_id = 0, int $is_private_post = 0) {
        try {
            ThreadRepository::create($title, $content, $forum_id, $classify_id, $is_private_post);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./forum', ['id' => $forum_id])
        ]);
    }

    public function editAction(int $id) {
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

    public function updateAction(int $id, Request $request) {
        try {
            $data = $request->validate([
                'classify_id' => 'int',
                'title' => 'string:0,200',
                'is_private_post' => 'int:0,9',
                'content' => 'required|string',
            ]);
            ThreadRepository::update($id, $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./thread', ['id' => $id])
        ], '更新成功');
    }

    public function replyAction(string $content, int $thread_id) {
        try {
            $post = ThreadRepository::reply($content, $thread_id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./thread', ['id' => $thread_id, 'page' => ceil($post->grade / 20)])
        ]);
    }

    public function digestAction(int $id) {
        try {
            ThreadRepository::threadAction($id, ['is_digest']);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function highlightAction(int $id) {
        try {
            ThreadRepository::threadAction($id, ['is_highlight']);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function closeAction(int $id) {
        try {
            ThreadRepository::threadAction($id, ['is_closed']);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function removePostAction(int $id) {
        try {
            ThreadRepository::removePost($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function collectAction(int $id) {
        try {
            $res = ThreadRepository::toggleCollect($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($res);
    }

    public function agreeAction(int $id) {
        try {
            $res = ThreadRepository::agreePost($id, true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($res);
    }

    public function disagreeAction(int $id) {
        try {
            $res = ThreadRepository::agreePost($id, false);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($res);
    }

    public function doAction(Request $request, int $id) {
        try {
            $model = ThreadPostModel::find($id);
            $html = Parser::create($model, $request)->render();
        } catch (StopNextException $ex) {
            return response();
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