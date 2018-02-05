<?php
namespace Module\Blog\Service;

use Module\Blog\Domain\Model\BlogLogModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\TermModel;
use Module\ModuleController;

class HomeController extends ModuleController {

    protected function rules() {
        return [
            'recommend' => '@',
            '*' => '*'
        ];
    }

    public function indexAction($sort = 'new', $category = null, $keywords = null) {
        $blog_list  = BlogModel::alias('b')
            ->left('term t', 'b.term_id = t.id')
            ->left('user u', 'u.id = b.user_id')
            ->select('b.id, b.title, b.description, b.created_at, b.comment_count, b.recommend, b.user_id, b.term_id, t.name as term_name, u.name as user_name')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('b.term_id', intval($category));
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                if ($sort == 'new') {
                    return $query->orderBy('b.created_at', 'desc');
                }
                if ($sort == 'recommend') {
                    return $query->orderBy('b.recommend', 'desc');
                }
                if ($sort == 'hot') {
                    return $query->orderBy('b.comment_count', 'desc');
                }
            })->when(!empty($keywords), function ($query) {
                BlogModel::search($query, ['b.title']);
            })
            ->page();
        $cat_list = TermModel::all();
        $log_list = [];
        return $this->show(compact('blog_list', 'cat_list', 'sort', 'category', 'keywords', 'log_list'));
    }

    public function detailAction($id) {
        $id = intval($id);
        $blog = BlogModel::alias('b')
            ->left('term t', 'b.term_id = t.id')
            ->left('user u', 'u.id = b.user_id')
            ->where('b.id', $id)
            ->select('b.*', 't.name as term_name, u.name as user_name')
            ->one();
        $cat_list = TermModel::all();
        $log_list = BlogLogModel::alias('l')
            ->left('blog b', 'b.id = l.id_value')
            ->left('user u', 'u.id = l.user_id')
            ->where('l.id_value', $id)
            ->where('l.type', BlogLogModel::TYPE_BLOG)
            ->order('l.created_at desc')
            ->select('l.*', 'b.title', 'u.name')
            ->limit(5)
            ->all();
        return $this->show(compact('blog', 'log_list', 'cat_list'));
    }

    public function recommendAction($id) {
        $id = intval($id);
        if (!BlogModel::canRecommend($id)) {
            return $this->jsonFailure('一个用户只能操作一次！');
        }
        $model = BlogModel::find($id);
        $model->recommend ++;
        $model->save();
        return $this->jsonSuccess($model->recommend);
    }

    public function suggestAction($keywords) {
        $data = BlogModel::when(!empty($keywords), function ($query) {
           BlogModel::search($query, 'title');
        })->limit(4)->pluck('title', 'id');
        return $this->jsonSuccess($data);
    }
}