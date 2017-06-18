<?php
namespace Module\Blog\Service;

use Module\Blog\Domain\Model\BlogModel;
use Module\ModuleController;

class HomeController extends ModuleController {

    public function indexAction(
        $keywords = null,
        $category = null,
        $sort = 'create_at',
        $order = 'desc',
        $page = 1,
        $size = 20) {
        $blog_list  = BlogModel::find()->alias('b')
            ->left('term t', 'b.term_id = t.id')
            ->left('user u', 'u.id = b.user_id')
            ->select('b.id, b.title, b.description, b.create_at, b.comment_count, b.recommend, b.user_id, b.term_id, t.name as term_name, u.name as user_name')
            ->page();
        return $this->show(compact('blog_list'));
    }

    public function detailAction($id) {
        $blog = BlogModel::find()->alias('b')
            ->left('term t', 'b.term_id = t.id')
            ->left('user u', 'u.id = b.user_id')
            ->where(['b.id' => intval($id)])
            ->select('b.*', 't.name as term_name, u.name as user_name')
            ->one();
        return $this->show(compact('blog'));
    }
}