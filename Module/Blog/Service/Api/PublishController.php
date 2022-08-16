<?php
declare(strict_types=1);
namespace Module\Blog\Service\Api;

use Domain\Repositories\FileRepository;
use Module\Blog\Domain\Repositories\BlogRepository;
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
            $model = BlogRepository::save($request->get(), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function detailAction(int $id, string $language = '') {
        try {
            $model = BlogRepository::sourceBlog($id, $language);
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
            BlogRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}