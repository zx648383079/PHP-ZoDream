<?php
declare(strict_types=1);
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookListModel;
use Module\Book\Domain\Model\ListItemModel;

class ListController extends Controller {

    public function indexAction(int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $model_list = BookListModel::with('user')->orderBy('created_at', SORT_DESC)->page();
        return $this->show(compact('model_list'));
    }

    public function detailAction(int $id) {
        BookListModel::where('id', $id)->updateIncrement('click_count');
        $list = BookListModel::find($id);
        $items = ListItemModel::with('book')->where('list_id', $id)->get();
        return $this->show('detail', compact('list', 'items'));
    }
}