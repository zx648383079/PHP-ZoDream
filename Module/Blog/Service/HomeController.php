<?php
namespace Module\Blog\Service;

use Module\Blog\Domain\Model\BlogLogModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\TermModel;
use Module\ModuleController;

class HomeController extends ModuleController {

    public function indexAction(
        $keywords = null,
        $category = null,
        $sort = 'create_at',
        $order = 'desc',
        $page = 1,
        $size = 20) {
        $blog_list  = BlogModel::alias('b')
            ->left('term t', 'b.term_id = t.id')
            ->left('user u', 'u.id = b.user_id')
            ->select('b.id, b.title, b.description, b.create_at, b.comment_count, b.recommend, b.user_id, b.term_id, t.name as term_name, u.name as user_name')
            ->page();
        $cat_list = TermModel::all();
        $log_list = BlogLogModel::alias('l')
            ->left('blog b', 'b.id = l.blog_id')
            ->left('user u', 'u.id = l.user_id')
            ->order('l.create_at desc')
            ->select('l.*', 'b.title', 'u.name')
            ->limit(5)
            ->all();
        return $this->show(compact('blog_list', 'cat_list', 'category', 'log_list'));
    }

    public function detailAction($id) {
        $id = intval($id);
        $blog = BlogModel::alias('b')
            ->left('term t', 'b.term_id = t.id')
            ->left('user u', 'u.id = b.user_id')
            ->where(['b.id' => intval($id)])
            ->select('b.*', 't.name as term_name, u.name as user_name')
            ->one();
        $cat_list = TermModel::where(['user_id' => $blog['user_id']])->all();
        $log_list = BlogLogModel::alias('l')
            ->left('blog b', 'b.id = l.blog_id')
            ->left('user u', 'u.id = l.user_id')
            ->where(['l.blog_id' => $id])
            ->order('l.create_at desc')
            ->select('l.*', 'b.title', 'u.name')
            ->limit(5)
            ->all();
        return $this->show(compact('blog', 'log_list', 'cat_list'));
    }

    public function recommendAction($id) {
        $id = intval($id);
        $model = BlogModel::find($id);
        $model->recommend ++;
        $model->save();
        return $this->jsonSuccess($model);
    }
}