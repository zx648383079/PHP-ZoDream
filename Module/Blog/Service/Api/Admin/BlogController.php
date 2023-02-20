<?php
namespace Module\Blog\Service\Api\Admin;

use Module\Blog\Domain\Repositories\PublishRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class BlogController extends Controller {

    public function indexAction(string $keywords = '', int $term = 0, int $status = 0, int $type = 0, string $language = '') {
        return $this->renderPage(
            PublishRepository::getList($keywords, $term, $status, $type, $language)
        );
    }

    public function detailAction(int $id = 0, string $language = '') {
        try {
            $model = PublishRepository::get($id, $language);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request, int $id = 0) {
        try {
            $model = PublishRepository::save($request->get(), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            PublishRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}