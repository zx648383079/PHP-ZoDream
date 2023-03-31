<?php
declare(strict_types=1);
namespace Module\Blog\Service\Api;

use Domain\Model\SearchModel;
use Module\Blog\Domain\Model\BlogContentModel;
use Module\Blog\Domain\Model\BlogSimpleModel;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Blog\Domain\Repositories\OptionRepository;
use Module\Blog\Domain\Repositories\StatisticsRepository;

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
            return $this->render(BlogRepository::detail($id, request('open_key', '')));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function contentAction(int $id) {
        try {
            return $this->render(BlogRepository::detailBody($id, request('open_key', '')));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function openAction(int $id) {
        try {
            return $this->render(BlogRepository::detailOpen($id, request('open_key', '')));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage(), $ex->getCode());
        }
    }

    public function recommendAction(int $id) {
        try {
            $model = BlogRepository::recommend($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function suggestAction(string $keywords) {
        $data = BlogSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'title');
        })->limit(4)->get();
        return $this->renderData($data);
    }

    public function subtotalAction() {
        return $this->render(StatisticsRepository::subtotal(auth()->id()));
    }

    public function editOptionAction() {
        return $this->render(OptionRepository::all());
    }
}