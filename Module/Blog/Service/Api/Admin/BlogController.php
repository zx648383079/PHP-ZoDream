<?php
namespace Module\Blog\Service\Api\Admin;

use Module\Blog\Domain\Repositories\BlogRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class BlogController extends Controller {

    public function indexAction(string $keywords = '', int $term = 0, int $type = 0) {
        return $this->renderPage(
            BlogRepository::getSelfList($keywords, $term, $type)
        );
    }

    public function detailAction(int $id, string $language = '') {
        try {
            $model = BlogRepository::sourceBlog($id, $language);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request, int $id = 0) {
        try {
            $model = BlogRepository::save($request->get(), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            BlogRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}