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
        $upload = new UploadInput();
        $result = $upload->setFile(Factory::root()
            ->file($this->configs['cache'].$upload->getName()))
            ->save();
        if (!$result) {
            return $this->jsonFailure($upload->getError());
        }
        return $this->jsonSuccess([
            'name' => $upload->getName(),
            'size' => $upload->getSize(),
            'type' => $upload->getType()
        ]);
    }
}