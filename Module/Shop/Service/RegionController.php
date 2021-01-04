<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\RegionModel;
use Zodream\Disk\File;
use Zodream\Service\Console\Input;

class RegionController extends Controller {

    public function rules()
    {
        return [
            'import' => 'cli',
            '*' => '*',
        ];
    }

    public function indexAction() {

    }

    public function treeAction() {
        return $this->renderData(RegionModel::cacheTree());
    }

    public function importAction(Input $input, $file = null) {
        $file = $this->getFile($file, $input);
        if (empty($file)) {
            return;
        }
        RegionModel::query()->delete();
        RegionModel::import($file);
        return '导入完成';
    }

    private function getFile($file, Input $input) {
        if (!empty($file) && is_file($file)) {
            return new File($file);
        }
        if (!empty($file)) {
            $file = app_path()->file($file);
            if ($file->exist()) {
                return $file;
            }
            $file = public_path()->file($file);
            if ($file->exist()) {
                return $file;
            }
        }
        return $this->getFile($input->post('请输入文件：', ''), $input);
    }
}