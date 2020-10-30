<?php
namespace Module\Blog\Service\Api;

use Infrastructure\Uploader;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Blog\Domain\Repositories\TagRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class PublishController extends RestController {

    protected function methods()
    {
        return [
            'index' => ['POST', 'PUT', 'PATCH'],
            'detail' => ['GET', 'HEAD', 'OPTIONS'],
            'upload' => ['POST'],
            'delete' => ['DELETE'],
        ];
    }

    protected function rules() {
        return [
            '*' => '@',
        ];
    }

    public function indexAction(Request $request) {
        try {
            $model = BlogRepository::save($request->get(), $request->get('id'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function detailAction($id, $language = '') {
        try {
            $model = BlogRepository::sourceBlog($id, $language);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function uploadAction() {
        $upload = new Uploader('file', [
            'pathFormat' => '/assets/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'maxSize' => 2048000,
            'allowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp', '.webp']
        ]);
        $data = $upload->getFileInfo();
        if ($data['state'] !== 'SUCCESS') {
            return $this->renderFailure($data['state']);
        }
        return $this->render($data);
    }

    public function deleteAction($id) {
        try {
            BlogRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}