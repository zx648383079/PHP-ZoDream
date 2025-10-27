<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api\Admin;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;
use Module\ResourceStore\Domain\Repositories\UploadRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class HomeController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0, int $category = 0) {
        return $this->renderPage(
            ResourceRepository::getManageList($keywords, $user, $category)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ResourceRepository::getEdit($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'title' => 'required|string:0,200',
                'description' => 'string:0,255',
                'keywords' => 'string:0,255',
                'thumb' => 'string:0,255',
                'content' => 'required',
                'size' => 'int',
                'preview_type' => 'int:0,127',
                'preview_file' => '',
                'cat_id' => 'required|int',
                'price' => 'int',
                'is_commercial' => 'int:0,127',
                'is_reprint' => 'int:0,127',
            ]);
            return $this->render(
                ResourceRepository::save($data, $input->get('tags', []), $input->get('files', []))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ResourceRepository::selfRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function fileAction(int $res_id, string $keywords = '') {
        try {
            ResourceRepository::selfGet($res_id);
            return $this->renderPage(ResourceRepository::fileList($res_id, $keywords));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function fileSaveAction(Input $input) {
        try {
            return $this->render(
                ResourceRepository::fileSave($input->validate([
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

    public function fileDeleteAction(int $id) {
        try {
            ResourceRepository::fileRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function uploadAction(Input $input) {
        try {
            return $this->render(UploadRepository::saveFile($input));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}