<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api;

use Module\Navigation\Domain\Repositories\SiteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

final class SiteController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0, string $domain = '') {
        return $this->renderPage(SiteRepository::getList($keywords, $category, $domain));
    }

    public function recommendAction(int $category = 0) {
        return $this->renderData(SiteRepository::recommend($category));
    }

    public function detailAction(int $id) {
        try {
            return $this->render(SiteRepository::get($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function categoryAction() {
        return $this->renderData(SiteRepository::categories());
    }

    public function categoryRecommendAction(int $category = 0) {
        return $this->renderData(SiteRepository::recommendGroup($category));
    }

    public function submitAction(Input $input) {
        try {
            $data = $input->validate([
                'title' => 'required|string:0,30',
                'description' => 'string:0,255',
                'thumb' => 'string',
                'link' => 'required|string:0,255',
            ]);
            return $this->render(SiteRepository::submit($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}