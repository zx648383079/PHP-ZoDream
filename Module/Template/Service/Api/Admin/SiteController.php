<?php
declare(strict_types=1);
namespace Module\Template\Service\Api\Admin;

use Module\Template\Domain\Repositories\SiteRepository;

class SiteController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(
           SiteRepository::getManageList($keywords, $user)
        );
    }

    public function toggleAction(int $id) {
        try {
            return $this->render(
                SiteRepository::manageToggle($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            SiteRepository::manageRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function pageAction(int $site = 0, string $keywords = '') {
        try {
            return $this->renderPage( SiteRepository::manageGetPage($keywords, $site));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function pageToggleAction(int $id) {
        try {
            return $this->render(
                SiteRepository::manageTogglePage($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function pageRemoveAction(int $id) {
        try {
            SiteRepository::manageRemovePage($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}