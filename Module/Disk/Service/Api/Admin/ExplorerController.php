<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api\Admin;

use Domain\Model\ModelHelper;
use Domain\Repositories\ExplorerRepository;
use Zodream\Html\Page;

class ExplorerController extends Controller {

    public function indexAction(string $path = '', string $keywords = '', string $filter = '', int $page = 1) {
        $items = ExplorerRepository::search($path, $keywords, $filter, $page);
        return $items instanceof Page ? $this->renderPage($items) : $this->renderData($items);
    }

    public function driveAction() {
        return $this->renderData(ExplorerRepository::driveList());
    }

    public function deleteAction(string $path) {
        try {
            ExplorerRepository::delete($path);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData(true);
    }

    public function storageAction(string $keywords = '', int $folder = 0) {
        return $this->renderPage(ExplorerRepository::storageSearch($keywords, $folder));
    }

    public function storageDeleteAction(int|array $id) {
        try {
            ExplorerRepository::storageRemove(ModelHelper::parseArrInt($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData(true);
    }

    public function storageReloadAction(int $folder) {
        try {
            ExplorerRepository::storageReload($folder);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData(true);
    }

    public function storageSyncAction(int|array $id) {
        try {
            ExplorerRepository::storageSync($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData(true);
    }
}