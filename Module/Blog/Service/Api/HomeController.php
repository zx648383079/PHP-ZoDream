<?php
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Model\BlogModel;
use Zodream\Route\Controller\RestController;

class HomeController extends RestController {

    protected function rules() {
        return [
            'recommend' => '@',
            '*' => '*'
        ];
    }

    public function indexAction($id = 0, $sort = 'new', $category = null, $keywords = null, $per_page = 20) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
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
            ->page($per_page);
        return $this->renderPage($blog_list);
    }

    public function detailAction($id) {
        $id = intval($id);
        BlogModel::where('id', $id)->updateOne('click_count');
        $blog = BlogModel::find($id);
        if (empty($blog)) {
            return $this->renderFailure('id 错误！');
        }
        return $this->render($blog->toArray());
    }

    public function recommendAction($id) {
        $id = intval($id);
        if (!BlogModel::canRecommend($id)) {
            return $this->renderFailure('一个用户只能操作一次！');
        }
        $blog = BlogModel::find($id);
        $blog->recommendThis();
        return $this->render($blog->toArray());
    }

    public function suggestAction($keywords) {
        $data = BlogModel::when(!empty($keywords), function ($query) {
           BlogModel::search($query, 'title');
        })->limit(4)->pluck('title', 'id');
        return $this->render(compact('data'));
    }
}