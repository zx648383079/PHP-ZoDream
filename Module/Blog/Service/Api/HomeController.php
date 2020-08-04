<?php
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Model\BlogContentModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogPageModel;
use Module\Blog\Domain\Model\BlogSimpleModel;
use Module\Blog\Domain\Repositories\BlogRepository;
use Zodream\Route\Controller\RestController;

class HomeController extends RestController {

    protected function rules() {
        return [
            'recommend' => '@',
            '*' => '*'
        ];
    }

    public function indexAction($id = 0, $sort = 'new', $category = null, $keywords = null,
                                $user = null, $language = null, $programming_language = null,
                                $tag = null, $per_page = 20) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $blog_list  = BlogRepository::getList($sort, $category, $keywords,
            $user, $language, $programming_language, $tag, $per_page);
        return $this->renderPage($blog_list);
    }

    public function detailAction($id) {
        $id = intval($id);
        BlogModel::where('id', $id)->updateOne('click_count');
        $blog = BlogModel::find($id);
        if (empty($blog) || $blog->open_type == BlogModel::OPEN_DRAFT) {
            return $this->renderFailure('id 错误！');
        }
        $data = $blog->toArray();
        $data['content'] = $blog->toHtml();
        return $this->render($data);
    }

    public function contentAction($id) {
        $id = intval($id);
        BlogModel::where('id', $id)->updateOne('click_count');
        $blog = BlogContentModel::find($id);
        if (empty($blog)) {
            return $this->renderFailure('id 错误！');
        }
        return $this->render($blog);
    }

    public function recommendAction($id) {
        $id = intval($id);
        if (!BlogModel::canRecommend($id)) {
            return $this->renderFailure('一个用户只能操作一次！');
        }
        $blog = BlogPageModel::find($id);
        $blog->recommendThis();
        return $this->render($blog);
    }

    public function suggestAction($keywords) {
        $data = BlogSimpleModel::when(!empty($keywords), function ($query) {
           BlogModel::searchWhere($query, 'title');
        })->limit(4)->get();
        return $this->render(compact('data'));
    }

    public function subtotalAction() {
        return $this->render(BlogRepository::subtotal());
    }
}