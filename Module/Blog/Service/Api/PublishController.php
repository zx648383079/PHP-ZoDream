<?php
declare(strict_types=1);
namespace Module\Blog\Service\Api;

use Domain\Repositories\FileRepository;
use Module\Blog\Domain\Repositories\PublishRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class PublishController extends Controller {

    public function methods()
    {
        return [
            'index' => ['POST', 'PUT', 'PATCH'],
            'detail' => ['GET', 'HEAD', 'OPTIONS'],
            'upload' => ['POST'],
            'delete' => ['DELETE'],
        ];
    }

    public function rules() {
        return [
            '*' => '@',
        ];
    }

    public function indexAction(Request $request, int $id = 0) {
        try {
            $model = PublishRepository::save($request->get(), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function pageAction(string $keywords = '', int $term = 0, int $status = 0, int $type = 0, string $language = '') {
        return $this->renderPage(
            PublishRepository::getList($keywords, $term, $status, $type, $language)
        );
    }

    public function saveDraftAction(Request $request, int $id = 0) {
        try {
            $model = PublishRepository::saveDraft($request->get(), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function detailAction(int $id = 0, string $language = '') {
        try {
            $model = PublishRepository::get($id, $language);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function uploadAction() {
        try {
            $res = FileRepository::uploadImage();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($res);
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