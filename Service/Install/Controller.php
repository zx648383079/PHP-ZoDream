<?php
namespace Service\Install;

use Service\Controller as BaseController;
use Zodream\Disk\File;

abstract class Controller extends BaseController {

    protected $canCSRFValidate = false;

    public File|string $layout = 'main';

    public function rules() {
        return [
            '*' => function() {
                if (is_file('install.off')) {
                    return $this->showContent('ZoDream安装程序已锁定。如果要重新安装，请删除<b>../install.off</b>文件！');
                }
                return true;
            }
        ];
    }

    public function prepare() {

    }
}