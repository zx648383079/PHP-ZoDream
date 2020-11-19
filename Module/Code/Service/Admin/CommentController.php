<?php
namespace Module\Code\Service\Admin;

use Module\Code\Domain\Model\CommentModel;

class CommentController extends Controller {

    public function indexAction($code_id = null, $keywords = null, $email = null, $name = null) {
        $comment_list = CommentModel::with('code')
            ->when(!empty($code_id), function ($query) use ($code_id) {
                $query->where('code_id', intval($code_id));
            })->when(!empty($email), function ($query) use ($email) {
                $query->where('email', $email);
            })->when(!empty($keywords), function ($query) {
                CommentModel::searchWhere($query, 'content');
            })->when(!empty($name), function ($query) {
                CommentModel::searchWhere($query, 'name', false, 'name');
            })->orderBy('id', 'desc')->page();
        return $this->show(compact('comment_list'));
    }

    public function deleteAction($id) {
        CommentModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('comment')
        ]);
    }
}