<?php
declare(strict_types=1);
namespace Module\Disk\Service\Admin;

use Domain\Model\ModelHelper;
use Domain\Repositories\ExplorerRepository;

class ExplorerController extends Controller {

    public function indexAction(string $path = '', string $keywords = '', string $filter = '', int $page = 1) {
        $driveItems = ExplorerRepository::driveList();
        $items = ExplorerRepository::search($path, $keywords, $filter, $page);
        return $this->show(compact('driveItems', 'items', 'path', 'keywords'));
    }

    public function deleteAction(string $path) {
        try {
            ExplorerRepository::delete($path);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'url' => $this->getUrl('explorer'),
            'no_jax' => true
        ]);
    }

    public function storageAction(string $keywords = '', int $tag = 0) {
        $items = ExplorerRepository::storageSearch($keywords, $tag);
        return $this->show(compact('items', 'keywords', 'tag'));
    }

    public function storageDeleteAction(int|array $id) {
        try {
            ExplorerRepository::storageRemove(ModelHelper::parseArrInt($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'url' => $this->getUrl('explorer/storage'),
            'no_jax' => true
        ]);
    }

    public function storageReloadAction(int $tag) {
        try {
            ExplorerRepository::storageReload($tag);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'url' => $this->getUrl('explorer/storage'),
            'no_jax' => true
        ]);
    }
}