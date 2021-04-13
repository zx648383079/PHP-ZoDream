<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api;

use Module\Forum\Domain\Error\StopNextException;
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
            'action' => '@',
            'do' => '@',
            '*' => '*'
        ];
    }

    public function indexAction(int $forum, int $classify = 0, string $keywords = '', int $user = 0, int $type = 0) {
        return $this->renderPage(
            ThreadRepository::getList($forum, $classify, $keywords, $user, $type)
        );
    }

    public function postAction(int $thread, int $user = 0, int $post = 0, int $per_page = 20) {
        try {
            return $this->renderPage(
                ThreadRepository::postList($thread, $user, $post, $per_page, 'json')
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function createAction(string $title, string $content,
                                 int $forum_id, int $classify_id = 0, int $is_private_post = 0) {
        try {
            return $this->render(
                ThreadRepository::create($title, $content, $forum_id, $classify_id, $is_private_post)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function editAction(int $id) {
        try {
            return $this->render(
                ThreadRepository::getSource($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ThreadRepository::getFull($id, true)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ThreadRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function updateAction(int $id, Request $request) {
        try {
            $data = $request->validate([
                'classify_id' => 'int',
                'title' => 'string:0,200',
                'is_private_post' => 'int:0,9',
                'content' => 'required|string',
            ]);
            return $this->render(
                ThreadRepository::update($id, $data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function replyAction(string $content, int $thread_id) {
        try {
            return $this->render(
                ThreadRepository::reply($content, $thread_id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function digestAction(int $id) {
        try {
            return $this->render(
                ThreadRepository::threadAction($id, ['is_digest'])
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function highlightAction(int $id) {
        try {
            return $this->render(
                ThreadRepository::threadAction($id, ['is_highlight'])
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function closeAction(int $id) {
        try {
            return $this->render(
                ThreadRepository::threadAction($id, ['is_closed'])
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function actionAction(int $id, array $action) {
        try {
            return $this->render(
                ThreadRepository::threadAction($id, $action)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function removePostAction(int $id) {
        try {
            ThreadRepository::removePost($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
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
            $html = Parser::create($model, $request)->render('json');
        } catch (StopNextException $ex) {
            return response();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'id' => $id,
            'content' => $html
        ]);
    }
}