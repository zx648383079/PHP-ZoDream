<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api;

use Exception;
use Module\Disk\Domain\Repositories\DiskRepository;
use Zodream\Domain\Upload\UploadInput;
use Zodream\Service\Http\Request;

class UploadController extends Controller {
    
    public function indexAction(Request $request) {
        $md5 = $request->server('HTTP_X_FILENAME');
        try {
            return $this->render(DiskRepository::driver()
                ->upload(new UploadInput(), $md5));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function chunkAction(Request $request, string $md5) {
        try {
            return $this->render(DiskRepository::driver()
                ->uploadChunk($request->file('file'), $md5));
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

    public function finishAction(string $md5, string $name, string $parent_id = '') {
        try {
            return $this->render(DiskRepository::driver()
                ->uploadFinish($md5, $name, $parent_id));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}