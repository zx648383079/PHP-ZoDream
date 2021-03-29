<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service;

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

    public function indexAction(string $sort = 'new', string $keywords = '', int $id = 0) {
        $blog_list = MicroRepository::getList($sort, $keywords, $id);
        return $this->show(compact('blog_list', 'keywords'));
    }

    public function createAction(Request $request) {
        if (!MicroRepository::canPublish()) {
            return $this->renderFailure('发送过于频繁！');
        }
        try {
            MicroRepository::create($request->get('content'), $request->get('file'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./')
        ]);
    }

    public function recommendAction(int $id, Request $request) {
        if (!$request->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = MicroRepository::recommend($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function collectAction(int $id, Request $request) {
        if (!$request->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = MicroRepository::collect($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function forwardMiniAction(int $id) {
        $this->layout = false;
        $blog = MicroBlogModel::find($id);
        return $this->show(compact('blog'));
    }

    public function forwardAction(Request $request, int  $id, string $content, bool $is_comment = false) {
        if (!$request->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = MicroRepository::forward($id, $content, $is_comment);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function deleteAction(int $id, Request $request) {
        if (!$request->isAjax()) {
            return $this->redirect('./');
        }
        try {
            MicroRepository::removeSelf($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }


}