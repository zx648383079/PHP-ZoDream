<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Admin;

use Module\ResourceStore\Domain\Models\ResourceModel;
use Module\ResourceStore\Domain\Repositories\CategoryRepository;
use Module\ResourceStore\Domain\Repositories\ResourceRepository;
use Module\ResourceStore\Domain\Repositories\UploadRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class PostController extends Controller {

    public function indexAction(string $keywords = '', int $cat_id = 0) {
        $post_list = ResourceRepository::getManageList($keywords, 0, $cat_id);
        $cat_list = CategoryRepository::levelTree();
        return $this->show(compact('post_list', 'cat_list', 'keywords', 'cat_id'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = $id > 0 ? ResourceRepository::get($id) : new ResourceModel();
        if (empty($model) || (!$model->isNewRecord && $model->user_id != auth()->id())) {
            return $this->redirectWithMessage($this->getUrl('post'), '文章不存在！');
        }
        $cat_list = CategoryRepository::levelTree();
        $tags = $id < 1 ? [] : ResourceRepository::tag()->getTags($id);
        return $this->show('edit', compact('model', 'cat_list', 'tags'));
    }

    public function saveAction(Input $input, array $tag, int $id = 0) {
        try {
            ResourceRepository::save($input->all(), $tag);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $id < 1 ? $this->getUrl('post') : -1
        ]);
    }

    public function deleteAction(int $id) {
        try {
            ResourceRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function uploadAction(Input $input) {
        try {
            $file = UploadRepository::saveFile($input);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($file);
    }
}