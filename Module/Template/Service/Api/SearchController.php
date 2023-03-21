<?php
declare(strict_types=1);
namespace Module\Template\Service\Api;

use Module\Template\Domain\Repositories\ComponentRepository;
use Zodream\Infrastructure\Contracts\Http\Output;

class SearchController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0, int $category = 0,
                                string $sort = 'created_at',
                                string|int|bool $order = 'desc') {
        return $this->renderPage(
            ComponentRepository::getList($keywords, $category, $user, $sort, $order)
        );
    }

    public function suggestionAction(string $keywords) {
        return $this->renderData(
            ComponentRepository::suggestion($keywords)
        );
    }

    public function dialogAction(string $keywords = '', int $category = 0,
                                int $type = 0, array|int $id = 0) {
        return $this->renderPage(
            ComponentRepository::dialogList($keywords, $category, $type, $id)
        );
    }
}