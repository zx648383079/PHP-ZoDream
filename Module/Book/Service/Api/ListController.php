<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookListModel;
use Module\Book\Domain\Model\ListItemModel;
use Zodream\Route\Controller\RestController;

class ListController extends RestController {

    public function indexAction() {
        $data = BookListModel::orderBy('created_at', 'desc')->page();
        return $this->renderPage($data);
    }

    public function detailAction($id) {
        $list = BookListModel::find($id);
        $items = ListItemModel::where('list_id', $id)->get();
        return $this->render(compact('list', 'items'));
    }
}