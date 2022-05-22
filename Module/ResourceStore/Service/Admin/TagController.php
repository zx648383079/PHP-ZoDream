<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Admin;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;

class TagController extends Controller {

    public function indexAction(string $keywords = '') {
        $model_list = ResourceRepository::tag()->getList($keywords);
        if (request()->isAjax() && !request()->header('X-PJAX')) {
            return $this->renderData($model_list);
        }
        return $this->show(compact('model_list'));
    }

    public function deleteAction(int $id) {
        ResourceRepository::tag()->remove($id);
        return $this->renderData([
            'url' => $this->getUrl('tag')
        ]);
    }
}