<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api;

use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Module\MicroBlog\Domain\Repositories\MicroRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class HomeController extends Controller {
    public function rules() {
        return [
            'recommend' => '@',
            'create' => '@',
            'forward' => '@',
            '*' => '*'
        ];
    }

    public function indexAction(string $sort = 'new',
                                string $keywords = '', int $id = 0, int $user = 0, int $topic = 0) {
        $items = MicroRepository::getList($sort, $keywords, $id, $user, $topic);
        return $this->renderPage($items);
    }

    public function createAction(Request $request) {
        if (!MicroRepository::canPublish()) {
            return $this->renderFailure('发送过于频繁！');
        }
        try {
            $model = MicroRepository::create($request->get('content'), $request->get('file'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function recommendAction(int $id) {
        try {
            $model = MicroRepository::recommend($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function collectAction(int $id) {
        try {
            $model = MicroRepository::collect($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function detailAction(int $id) {
        $blog = MicroBlogModel::find($id);
        $blog->user;
        $blog->attachment;
        return $this->render($blog);
    }

    public function forwardAction(int $id, string $content, bool $is_comment = false) {
        try {
            $model = MicroRepository::forward($id, $content, $is_comment);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            MicroRepository::removeSelf($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}