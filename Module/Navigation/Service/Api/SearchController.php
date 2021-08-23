<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api;

use Module\Navigation\Domain\Repositories\SearchRepository;

final class SearchController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(SearchRepository::getList($keywords));
    }

    public function suggestAction(string $keywords = '') {
        return $this->renderData(SearchRepository::suggest($keywords));
    }

}