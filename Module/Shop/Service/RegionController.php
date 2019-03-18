<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Model\RegionModel;
use Zodream\Disk\File;
use Zodream\Service\Factory;

class RegionController extends Controller {

    protected function rules()
    {
        return [
            'import' => 'cli',
            '*' => '*',
        ];
    }

    public function indexAction() {

    }

    public function treeAction() {
        return $this->jsonSuccess(RegionModel::cacheTree());
    }

    public function importAction($file = null) {
        $file = $this->getFile($file);
        if (empty($file)) {
            return;
        }
        RegionModel::query()->delete();
        RegionModel::import($file);
        return '导入完成';
    }

    private function getFile($file) {
        if (!empty($file) && is_file($file)) {
            return new File($file);
        }
        if (!empty($file)) {
            $file = Factory::root()->file($file);
            if ($file->exist()) {
                return $file;
            }
            $file = Factory::public_path()->file($file);
            if ($file->exist()) {
                return $file;
            }
        }
        return $this->getFile(app('request')->read('', '请输入文件：'));
    }
}