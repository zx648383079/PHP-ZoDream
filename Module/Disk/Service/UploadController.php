<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\Repositories\DiskRepository;
use Zodream\Domain\Upload\UploadInput;
use Zodream\Service\Http\Request;

/**
 * 上传
 * Class UploadController
 * @package Module\Disk\Service
 */
class UploadController extends Controller {
    
    public function indexAction(Request $request) {
        set_time_limit(0);
        $md5 = $request->server('HTTP_X_FILENAME');
        $name = session('file_'.$md5);
        if (empty($name)) {
            $name = $md5;
        }
        $upload = new UploadInput();
        $result = $upload->setName($name)
            ->setFile(DiskRepository::driver()->cacheFolder()->file($md5))
            ->save();
        if (!$result) {
            return $this->renderFailure($upload->getError());
        }
        logger()->info($name);
        return $this->renderData([
            'name' => $upload->getName(),
            'size' => $upload->getSize(),
            'type' => $upload->getType()
        ]);
    }
}