<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookListModel;
use Module\Book\Domain\Model\ListItemModel;

class ListController extends Controller {

    public function indexAction($id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        if (request()->isMobile()) {
            return $this->redirect('./mobile/list');
        }
        $model_list = BookListModel::with('user')->orderBy('created_at', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function detailAction($id) {
        BookListModel::where('id', $id)->updateOne('click_count');
        $list = BookListModel::find($id);
        $items = ListItemModel::with('book')->where('list_id', $id)->get();
        return $this->show('detail', compact('list', 'items'));
    }
}