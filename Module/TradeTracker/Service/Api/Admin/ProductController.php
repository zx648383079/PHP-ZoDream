<?php
declare(strict_types=1);
namespace Module\TradeTracker\Service\Api\Admin;

use Module\TradeTracker\Domain\Repositories\ManagerRepository;
use Module\TradeTracker\Domain\Repositories\ProductRepository;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Infrastructure\Contracts\Http\Input;

class ProductController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0, int $project = 0) {
        return $this->renderPage(
            ProductRepository::getProductList($keywords, $category, $project)
        );
    }

    public function addAction(Input $input) {
        
    }

    public function deleteAction(int $id) {
        try {
            ManagerRepository::productRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function channelAction(string $keywords = '') {
        return $this->renderPage(ManagerRepository::channelList($keywords));
    }

    public function channelSaveAction(Input $input) {
        try {
            return $this->render(
                ManagerRepository::channelSave($input->validate([
                  'id' => 'int',
                  'short_name' => 'string:0,20',
                    'name' => 'required|string:0,40',
                    'site_url' => 'string:0,255',
              ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function channelDeleteAction(int $id) {
        try {
            ManagerRepository::channelRemove($id);
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
                ManagerRepository::productImport($file->getFile());
            });
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}