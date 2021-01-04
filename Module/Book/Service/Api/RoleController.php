<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookRoleModel;
use Module\Book\Domain\Model\RoleRelationModel;

class RoleController extends Controller {

    public function indexAction($book) {
        $data = BookRoleModel::where('book_id', $book)->page();
        return $this->renderPage($data);
    }

    public function relationAction($id) {
        $role = BookRoleModel::find($id);
        $relation = RoleRelationModel::where('role_id', $id)->orWhere('role_link', $id)->all();
        return $this->render(compact('role', 'relation'));
    }
}