<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api\Admin;

use Module\Navigation\Domain\Repositories\Admin\SiteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

final class SiteController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0, int $category = 0, string $domain = '') {
        return $this->renderPage(SiteRepository::getList($keywords, $category, $user, $domain));
    }

    public function detailAction(int $id) {
        try {
            return $this->render(SiteRepository::get($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([

            ]);
            return $this->render(SiteRepository::save($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            SiteRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}