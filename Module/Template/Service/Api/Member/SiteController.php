<?php
declare(strict_types=1);
namespace Module\Template\Service\Api\Member;

use Module\Template\Domain\Repositories\SiteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class SiteController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
           SiteRepository::selfList($keywords)
        );
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'title' => 'string:0,200',
                'keywords' => 'string:0,255',
                'thumb' => 'string:0,255',
                'page' => 'string:0,255',
                'description' => 'string:0,255',
                'domain' => 'string:0,50',
                'default_page_id' => 'int',
                'is_share' => 'int:0,127',
                'share_price' => 'int',
            ]);
            return $this->render(
                SiteRepository::selfSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            SiteRepository::selfRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function pageAction(int $site, string $keywords = '') {
        try {
            return $this->renderPage(SiteRepository::selfGetPage($site, $keywords));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function pageSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'site_id' => 'required|int',
                'component_id' => 'required|int',
                'type' => 'int:0,127',
                'name' => 'required|string:0,100',
                'title' => 'string:0,200',
                'keywords' => 'string:0,255',
                'thumb' => 'string:0,255',
                'description' => 'string:0,255',
                'settings' => '',
                'position' => 'int:0,127',
                'dependencies' => 'string:0,255',
            ]);
            return $this->render(
                SiteRepository::selfSavePage($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function pageRemoveAction(int $id) {
        try {
            SiteRepository::selfRemovePage($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function componentAction(int $site, string $keywords = '', int $type = 0) {
        try {
            return $this->renderPage(SiteRepository::selfGetComponent($site, $keywords, $type));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function componentAddAction(int $site, array|int $id) {
        try {
            SiteRepository::selfAddComponent($site, $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function componentRemoveAction(int $id) {
        try {
            SiteRepository::selfRemoveComponent($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}