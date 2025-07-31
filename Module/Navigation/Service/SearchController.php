<?php
declare(strict_types=1);
namespace Module\Navigation\Service;


use Module\Navigation\Domain\Repositories\SearchRepository;

class SearchController extends Controller {

    public function indexAction(string $keywords = '') {
        if (empty($keywords)) {
            return $this->redirect('./');
        }
        $items = SearchRepository::getList($keywords);
        return $this->show(compact('items', 'keywords'));
    }

    public function suggestAction(string $keywords = '') {
        return $this->renderData(
            SearchRepository::suggest($keywords)
        );
    }
}