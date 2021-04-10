<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api\Admin;

use Module\CMS\Domain\Repositories\LinkageRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class LinkageController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            LinkageRepository::getList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                LinkageRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'type' => 'int:0,9',
                'code' => 'required|string:0,20',
            ]);
            return $this->render(
                LinkageRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            LinkageRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function dataAction(int $linkage, string $keywords = '', int $parent = 0) {
        return $this->renderPage(
            LinkageRepository::dataList($linkage, $keywords, $parent)
        );
    }

    public function treeAction(int $id) {
        return $this->renderData(LinkageRepository::tree($id));
    }

    public function dataSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'linkage_id' => 'required|int',
                'name' => 'required|string:0,100',
                'parent_id' => 'int',
                'position' => 'int:0,999',
            ]);
            return $this->render(
                LinkageRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function dataDeleteAction(int $id) {
        try {
            LinkageRepository::dataRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}