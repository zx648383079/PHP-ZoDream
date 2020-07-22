<?php
namespace Module\Blog\Service\Api;

use Infrastructure\Uploader;
use Module\Blog\Domain\Model\BlogModel;
use Zodream\Route\Controller\RestController;

class PublishController extends RestController {

    protected function rules() {
        return [
            '*' => '@',
        ];
    }

    public function indexAction($title) {
        $title = trim($title);
        if (empty($title)) {
            return $this->renderFailure('请输入标题');
        }
        $model = BlogModel::where('title', $title)->where('user_id', auth()->id())->first();
        if (!$model) {
            $model = new BlogModel();
        }
        if (!$model->load(null, ['user_id'])) {
            return $this->renderFailure($model->getFirstError());
        }
        $model->user_id = auth()->id();
        $model->comment_status = 0;
        if (!$model->save()) {
            return $this->renderFailure($model->getFirstError());
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