<?php
declare(strict_types=1);
namespace Module\TradeTracker\Service\Api\Admin;

use Module\TradeTracker\Domain\Repositories\ManagerRepository;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Infrastructure\Contracts\Http\Input;

class LogController extends Controller {

    public function indexAction(int $product = 0, int $channel = 0, int $type = 0) {
        return $this->renderPage(ManagerRepository::logList($product, $channel, $type));
    }

    public function addAction(Input $input) {
        try {
            ManagerRepository::logAdd($input->validate([
                'product' => 'required|string',
                'channel' => 'required|string',
                'type' => 'int',
                'price' => 'required|numeric',
                'order_count' => 'int',
                'created_at' => '',
            ]));
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int|array $id) {
        try {
            ManagerRepository::logRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
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

    public function crawlAction(Input $input) {
        try {
            $data = $input->validate([
                'from' => 'string',
                'name' => 'string',
                'items' => '',
            ]);
            ManagerRepository::crawlSave($data);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}