<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api;

use Exception;
use Module\Disk\Domain\Repositories\DiskRepository;
use Zodream\Infrastructure\Contracts\Http\Output;

class HomeController extends Controller {

    public function indexAction(string $id, string $path = '') {
        return $this->renderPage(DiskRepository::driver()->catalog($id, $path));
    }

    public function searchAction(string $keywords = '', string $type = '') {
        return $this->renderPage(DiskRepository::driver()->search($keywords, $type));
    }

    public function createAction(string $name, string $parent_id = '') {
        try {
            return $this->renderData(DiskRepository::driver()
                ->create($name, $parent_id));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function renameAction(int $id, string $name) {
        try {
            return $this->renderData(DiskRepository::driver()
                ->rename($id, $name));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(string $id) {
        try {
            DiskRepository::driver()->remove($id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    /**
     * 进行网址访问许可
     * @param $url
     * @return Output
     * @throws Exception
     */
    public function allowAction(string $url) {
        if (empty($url)) {
            return $this->renderFailure('网址必传');
        }
        try {
            return $this->renderData(
                DiskRepository::allowUrl($url)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function fileAction(string $id) {
        try {
            return $this->render(DiskRepository::file($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function filesAction(array $id) {
        try {
            return $this->render(DiskRepository::files($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}