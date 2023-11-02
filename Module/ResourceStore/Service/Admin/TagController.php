<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Admin;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;

class TagController extends Controller {

    public function indexAction(string $keywords = '') {
        $items = ResourceRepository::tag()->getList($keywords);
        if (request()->isAjax() && !request()->header('X-PJAX')) {
            return $this->renderData($items);
        }
        return $this->show(compact('items'));
    }

    public function deleteAction(int $id) {
        ResourceRepository::tag()->remove($id);
        return $this->renderData([
            'url' => $this->getUrl('tag')
        ]);
    }
}