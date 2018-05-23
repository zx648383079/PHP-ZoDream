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
        $file = $this->diskFolder->file($model->file->location);
        if (!$file->exist()) {
            return $this->jsonFailure('FILE ERROR!');
        }
        $file->setExtension($model->file->extension)
            ->setName($model->name);
        return Factory::response()
            ->file($file);
    }
}