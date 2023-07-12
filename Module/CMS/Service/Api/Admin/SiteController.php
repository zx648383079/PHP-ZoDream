<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api\Admin;

use Module\CMS\Domain\Repositories\SiteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class SiteController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            SiteRepository::getList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                SiteRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'title' => 'required|string:0,255',
                'keywords' => 'string:0,255',
                'description' => 'string:0,255',
                'logo' => 'string:0,255',
                'theme' => 'required|string:0,100',
                'match_type' => 'int:0,127',
                'match_rule' => 'string:0,100',
                'is_default' => 'int:0,127',
                'status' => 'int:0,127',
                'language' => 'string:0,10',
                'options' => '',
            ]);
            return $this->render(
                SiteRepository::save($data)
            );
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

    public function defaultAction(int $id) {
        try {
            SiteRepository::setDefault($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function optionAction(int $id) {
        try {
            return $this->renderData(SiteRepository::option($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function optionSaveAction(int $id, array $option = []) {
        try {
            SiteRepository::optionSave($id, $option);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}