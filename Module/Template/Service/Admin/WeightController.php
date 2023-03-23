<?php
declare(strict_types=1);
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\SitePageModel;
use Module\Template\Domain\Repositories\PageRepository;
use Module\Template\Domain\Repositories\SiteRepository;

class WeightController extends Controller {

    public function indexAction(int $id) {
        $siteId = SitePageModel::where('id', $id)->value('site_id');
        $weights = SiteRepository::weightGroups($siteId);
        return $this->renderData(compact('weights'));
    }

    public function settingAction(int $id) {
        return $this->renderData(PageRepository::weight($id));
    }

    public function createAction(int $page_id, int $weight_id,
                                 int $parent_id, int $parent_index = 0, int $position = 0, int $group = 0) {
        try {
            return $this->renderData(PageRepository::weightAdd($page_id, $weight_id, $parent_id, $parent_index, $position, $group));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function refreshAction(int $id) {
        try {
            return $this->renderData(PageRepository::weightRefresh($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(int $id) {
        try {
            return $this->renderData(PageRepository::weightSave($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function moveAction(int $id, int $parent_id, int $parent_index = 0, int $position = 0) {
        try {
            PageRepository::weightMove($id, $parent_id, $parent_index, $position);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function destroyAction(int $id) {
        try {
            PageRepository::weightRemove($id);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function batchSaveAction(int $id, array $weights) {
        try {
            PageRepository::batchSave($id, $weights);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function formAction(int $id) {
        try {
            return $this->renderData(
                PageRepository::weightForm($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}