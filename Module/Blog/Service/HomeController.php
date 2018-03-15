<?php
namespace Module\Blog\Service;

use Module\Blog\Domain\Model\BlogLogModel;
use Module\Blog\Domain\Model\BlogModel;
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
        $blog_list  = BlogModel::with('term', 'user')
            ->select('id', 'title', 'description',
                'created_at', 'comment_count',
                'click_count', 'recommend', 'user_id', 'term_id')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('term_id', intval($category));
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                if ($sort == 'new') {
                    return $query->orderBy('created_at', 'desc');
                }
                if ($sort == 'recommend') {
                    return $query->orderBy('recommend', 'desc');
                }
                if ($sort == 'hot') {
                    return $query->orderBy('comment_count', 'desc');
                }
            })->when(!empty($keywords), function ($query) {
                BlogModel::search($query, ['title']);
            })
            ->page();
        $cat_list = TermModel::all();
        $log_list = [];
        $new_list = BlogModel::order('created_at', 'desc')
            ->select('id', 'title')
            ->limit(4)->all();
        return $this->show(compact('blog_list',
            'cat_list', 'sort', 'category', 'keywords',
            'log_list', 'new_list'));
    }

    public function detailAction($id) {
        $id = intval($id);
        BlogModel::record()->where('id', $id)->updateOne('click_count');
        $blog = BlogModel::find($id);
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