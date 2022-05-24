<?php
declare(strict_types=1);
namespace Module\AppStore\Service\Api\Admin;

use Module\AppStore\Domain\Repositories\AppRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class SoftwareController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0, int $category = 0) {
        return $this->renderPage(
            AppRepository::getManageList($keywords, $user, $category)
        );
    }

    public function detailAction(int $id, int $version = 0) {
        try {
            return $this->render(
                AppRepository::getEdit($id, $version)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'cat_id' => 'required|int',
                'name' => 'required|string:0,20',
                'keywords' => 'string:0,255',
                'description' => 'string:0,255',
                'content' => '',
                'icon' => 'string:0,255',
                'is_free' => 'int:0,127',
                'is_open_source' => 'int:0,127',
                'official_website' => 'string:0,255',
                'git_url' => 'string:0,255',
                'score' => 'numeric',
            ]);
            return $this->render(
                AppRepository::save($data, $input->get('tags', []))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            AppRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function versionAction(int $software, string $keywords = '') {
        try {
            AppRepository::getSelf($software);
            return $this->renderPage(AppRepository::versionList($software, $keywords));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function versionNewAction(Input $input) {
        try {
            return $this->render(
                AppRepository::versionCreate($input->validate([
                    'app_id' => 'required|int',
                    'name' => 'required|string:0,20',
                    'description' => 'string:0,255',
            ])));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function versionRemoveAction(int $id) {
        try {
            AppRepository::versionRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function packageAction(int $software, int $version = 0, string $keywords = '') {
        try {
            AppRepository::getSelf($software);
            return $this->renderPage(AppRepository::packageList($software, $version, $keywords));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function packageSaveAction(Input $input) {
        try {
            return $this->render(
                AppRepository::packageSave($input->validate([
                    'id' => 'int',
                    'app_id' => 'required|int',
                    'version_id' => 'required|int',
                    'os' => 'string:0,20',
                    'framework' => 'string:0,10',
                    'url_type' => 'int:0,127',
                    'url' => 'string:0,255',
                    'size' => 'int',
                ])));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function packageRemoveAction(int $id) {
        try {
            AppRepository::packageRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}