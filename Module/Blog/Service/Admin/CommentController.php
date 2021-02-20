<?php
namespace Module\Blog\Service\Admin;

use Domain\Model\SearchModel;
use Module\Blog\Domain\Model\CommentModel;

class CommentController extends Controller {

    public function indexAction($blog_id = null, $keywords = null, $email = null, $name = null) {
        $comment_list = CommentModel::with('blog')
            ->when(!empty($blog_id), function ($query) use ($blog_id) {
                $query->where('blog_id', intval($blog_id));
            })->when(!empty($email), function ($query) use ($email) {
                $query->where('email', $email);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'content');
            })->when(!empty($name), function ($query) {
                SearchModel::searchWhere($query, 'name', false, 'name');
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