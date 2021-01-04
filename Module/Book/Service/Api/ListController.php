<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Repositories\ListRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class ListController extends Controller {

    public function rules() {
        return [
            'index' => '*',
            'detail' => '*',
            '*' => '@'
        ];
    }

    public function indexAction($keywords = '') {
        $data = ListRepository::getList($keywords);
        return $this->renderPage($data);
    }

    public function detailAction($id) {
        try {
            $model = ListRepository::detail($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        try {
            $data = $request->validate([
                'title' => 'required|string',
                'description' => '',
                'id' => '',
                'items' => ''
            ]);
            $model = ListRepository::save($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction($id) {
        try {
            ListRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function collectAction($id) {
        try {
            $model = ListRepository::collect($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function agreeAction($id) {
        try {
            $model = ListRepository::agree($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function disagreeAction($id) {
        try {
            $model = ListRepository::disagree($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}