<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\Model\DiskModel;
use Zodream\Service\Factory;

/**
 * 下载
 * Class DownloadController
 * @package Module\Disk\Service
 */
class DownloadController extends Controller {
    
    public function indexAction($id) {
        $model = DiskModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('ID ERROR!');
        }
        $file = Factory::root()->file($this->configs['disk'].$model->location);
        if (!is_file($file)) {
            return $this->jsonFailure('FILE ERROR!');
        }
        return Factory::response()
            ->file($file);
    }
}