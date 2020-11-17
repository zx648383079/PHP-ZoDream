<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookListModel;
use Module\Book\Domain\Model\ListItemModel;
use Module\Book\Domain\Repositories\ListRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class ListController extends RestController {

    protected function rules()
    {
        return [
            'save' => '@',
            '*' => '*'
        ];
    }

    public function indexAction() {
        $data = BookListModel::with('user', 'items')->orderBy('created_at', 'desc')->page();
        return $this->renderPage($data);
    }

    public function detailAction($id) {
        $list = BookListModel::find($id);
        $list->user;
        $list->items = ListItemModel::with('book')->where('list_id', $id)->get();
        return $this->render($list);
    }

    public function saveAction(Request $request) {
        try {
            $data = $request->validate([
                'title' => 'required|string',
                'description' => '',
                'id' => '',
                'items' => ''
            ]);
            $model = ListRepository::save($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}