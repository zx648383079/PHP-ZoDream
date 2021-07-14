<?php
namespace Module\WeChat\Service\Admin;

use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    public File|string $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }

    public function weChatId(int $id = -1) {
        static $wid = 0;
        if ($id > 0) {
            session([
                'wid' => $id
            ]);
            return $wid = $id;
        }
        if ($wid < 1) {
            $wid = intval(session('wid'));
        }
        return $wid;
    }

    protected function processRule($rule) {
        if ($rule == 'w') {
            return !empty($this->weChatId()) ?: $this->redirect($this->getUrl('manage'));
        }
        return parent::processRule($rule);
    }
}