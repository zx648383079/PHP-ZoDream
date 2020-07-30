<?php
namespace Module\Blog\Service\Api;

use Infrastructure\Uploader;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Blog\Domain\Repositories\TagRepository;
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

    public function detailAction($id, $language = '') {
        $model = BlogModel::getOrNew($id, $language);
        if (empty($model) || (!$model->isNewRecord && $model->user_id != auth()->id())) {
            return $this->renderFailure('博客不存在');
        }
        $tags = $model->isNewRecord ? [] : TagRepository::getTags($model->id);
        $data = $model->toArray();
        $data['tags'] = $tags;
        return $this->render($data);
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
        $model = BlogModel::where('id', $id)->where('user_id', auth()->id());
        if (empty($model)) {
            return $this->renderFailure('文章不存在');
        }
        $model->delete();
        if ($model->parent_id < 1) {
            BlogModel::where('parent_id', $id)->delete();
        }
        return $this->renderData(true);
    }
}