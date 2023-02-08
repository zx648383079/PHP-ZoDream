<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api;

use Exception;
use Module\Disk\Domain\Repositories\DiskRepository;
use Zodream\Service\Http\Request;

class UploadController extends Controller {
    
    public function indexAction(Request $request, string $name, string $md5, string $parent_id = '') {
        try {
            return $this->render(DiskRepository::driver()
                ->uploadFile($request->file('file'), $md5, $name, $parent_id));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function chunkAction(Request $request, string $name) {
        try {
            return $this->render(DiskRepository::driver()
                ->uploadChunk($request->file('file'), $name));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function checkAction(string $md5, string $name, string $parent_id = '') {
        try {
            return $this->render(DiskRepository::driver()
                ->uploadCheck($md5, $name, $parent_id));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function finishAction(string $md5, string $name, array $files, string $parent_id = '') {
        try {
            return $this->render(DiskRepository::driver()
                ->uploadFinish($md5, $name, $files, $parent_id));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}