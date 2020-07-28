<?php
namespace Module\Blog\Service\Api;

use Infrastructure\Uploader;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Repositories\BlogRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class PublishController extends RestController {

    protected function rules() {
        return [
            '*' => '@',
        ];
    }

    public function indexAction(Request $request) {
        try {
            $model = BlogRepository::publish($request->get());
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
}