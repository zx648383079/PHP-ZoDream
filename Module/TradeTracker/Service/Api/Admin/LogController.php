<?php
declare(strict_types=1);
namespace Module\TradeTracker\Service\Api\Admin;

use Module\TradeTracker\Domain\Repositories\ManagerRepository;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;

class LogController extends Controller {

    public function indexAction() {
    }

    public function importAction() {
        try {
            $upload = new Upload();
            $upload->setDirectory(app_path()->directory('data/cache'));
            $upload->upload('file');
            if (!$upload->checkType('zip') || !$upload->save()) {
                return $this->renderFailure('文件不支持，仅支持zip压缩文件');
            }
            $upload->each(function (BaseUpload $file) {
                ManagerRepository::logImport($file->getFile());
            });
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}