<?php
declare(strict_types=1);
namespace Module\Blog\Service\Api;

use Domain\Model\SearchModel;
use Module\Blog\Domain\Model\BlogContentModel;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogPageModel;
use Module\Blog\Domain\Model\BlogSimpleModel;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Blog\Domain\Repositories\OptionRepository;

class HomeController extends Controller {

    public function rules() {
        return [
            'recommend' => '@',
            '*' => '*'
        ];
    }

    public function indexAction(int $id = 0, string $sort = 'new', int $category = 0,
                                string $keywords = '',
                                int $user = 0, string $language = '', string $programming_language = '',
                                string $tag = '', int $per_page = 20) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $blog_list  = BlogRepository::getList($sort, $category, $keywords,
            $user, $language, $programming_language, $tag, $per_page);
        return $this->renderPage($blog_list);
    }

    public function detailAction(int $id) {
        try {
            return $this->render(BlogRepository::detail($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function contentAction(int $id) {
        $id = intval($id);
        BlogModel::where('id', $id)->updateIncrement('click_count');
        $blog = BlogContentModel::find($id);
        if (empty($blog)) {
            return $this->renderFailure('id 错误！');
        }
        return $this->render($blog);
    }

    public function recommendAction(int $id) {
        if (!BlogModel::canRecommend($id)) {
            return $this->renderFailure('一个用户只能操作一次！');
        }
        $blog = BlogPageModel::find($id);
        $blog->recommendThis();
        return $this->render($blog);
    }

    public function suggestAction(string $keywords) {
        $data = BlogSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'title');
        })->limit(4)->get();
        return $this->render(compact('data'));
    }

    public function subtotalAction() {
        return $this->render(BlogRepository::subtotal());
    }

    public function editOptionAction() {
        return $this->render(OptionRepository::all());
    }
}