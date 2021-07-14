<?php
namespace Module\Shop\Service\Mobile;

use Module\ModuleController;
use Module\Shop\Domain\Models\ArticleModel;
use Module\Shop\Domain\Models\CategoryModel;
use Zodream\Disk\File;


class Controller extends ModuleController {

    public File|string $layout = '/Mobile/layouts/main';
    protected $disallow = true;

    protected function getUrl($path, $args = []) {
        return url('./mobile/'.$path, $args);
    }

    protected function runActionMethod($action, $vars = array()) {
        if (app()->isDebug() || !$this->disallow) {
            return parent::runActionMethod($action, $vars);
        }
        return $this->redirect('/');
    }


    public function redirectWithAuth() {
        return $this->redirect(['./mobile/member/login', 'redirect_uri' => url()->full()]);
    }
}