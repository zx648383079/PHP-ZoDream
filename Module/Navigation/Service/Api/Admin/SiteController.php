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
                'id' => 'int',
                'schema' => 'string:0,10',
                'domain' => 'required|string:0,100',
                'name' => 'required|string:0,30',
                'logo' => 'string:0,255',
                'description' => 'string:0,255',
                'cat_id' => 'int',
                'top_type' => 'int:0,100',
                'tags' => '',
                'also_page' => '',
                'keywords' => '',
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

    public function scoringAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'required|int',
                'score' => 'required|int:0,127',
                'change_reason' => 'required|string'
            ]);
            return $this->render(SiteRepository::scoring($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function scoreLogAction(int $site) {
        return $this->renderPage(SiteRepository::getScoreLog($site));
    }
}