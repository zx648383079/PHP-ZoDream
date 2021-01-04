<?php
namespace Module\WeChat\Service\Admin;

use Module\ModuleController;

class Controller extends ModuleController {

    public $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }

    public function weChatId($id = null) {
        static $wid;
        if (!is_null($id)) {
            session([
                'wid' => $id
            ]);
            return $wid = $id;
        }
        if (empty($wid)) {
            $wid = session('wid');
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