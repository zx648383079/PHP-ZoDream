<?php
namespace Module\Disk\Service;

use Zodream\Domain\Upload\UploadInput;

use Zodream\Service\Factory;

/**
 * 上传
 * Class UploadController
 * @package Module\Disk\Service
 */
class UploadController extends Controller {
    
    public function indexAction() {
        set_time_limit(0);
        $md5 = app('request')->server('HTTP_X_FILENAME');
        $name = Factory::session('file_'.$md5);
        if (empty($name)) {
            $name = $md5;
        }
        $upload = new UploadInput();
        $result = $upload->setName($name)
            ->setFile($this->cacheFolder->file($md5))
            ->save();
        if (!$result) {
            return $this->renderFailure($upload->getError());
        }
        Factory::log()->info($name);
        return $this->renderData([
            'name' => $upload->getName(),
            'size' => $upload->getSize(),
            'type' => $upload->getType()
        ]);
    }
}