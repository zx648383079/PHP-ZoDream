<?php
namespace Module\MicroBlog\Service\Admin;

use Module\MicroBlog\Domain\Model\CommentModel;

class CommentController extends Controller {

    public function indexAction($micro_id = null, $keywords = null, $email = null, $name = null) {
        $comment_list = CommentModel::with('micro')
            ->when(!empty($micro_id), function ($query) use ($micro_id) {
                $query->where('micro_id', intval($micro_id));
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
        return $this->jsonSuccess([
            'url' => $this->getUrl('comment')
        ]);
    }
}