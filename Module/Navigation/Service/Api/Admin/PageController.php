<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api\Admin;

use Module\Navigation\Domain\Repositories\Admin\PageRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

final class PageController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0, int $site = 0, string $domain = '') {
        return $this->renderPage(PageRepository::getList($keywords, $user, $site, $domain));
    }

    public function detailAction(int $id) {
        try {
            return $this->render(PageRepository::get($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'title' => 'required|string:0,30',
                'description' => 'string:0,255',
                'thumb' => 'string:0,255',
                'link' => 'required|string:0,255',
                'site_id' => 'string:0,255',
                'page_rank' => 'string:0,255',
            ]);
            return $this->render(PageRepository::save($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            PageRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}