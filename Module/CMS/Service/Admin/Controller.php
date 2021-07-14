<?php
namespace Module\CMS\Service\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\ModuleController;
use Zodream\Disk\File;


class Controller extends ModuleController {

    use CheckRole;

    public File|string $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => 'cms_admin'
        ];
    }

    public function prepare() {
        CMSRepository::resetSite();
        $cat_menu = CategoryModel::tree()->makeTreeForHtml();
        $cat_menu = array_filter($cat_menu, function ($item) {
            return $item['type'] < 1;
        });
        $form_list = ModelModel::where('type', 1)->get('id,name');
        $this->send(compact('cat_menu', 'form_list'));
    }

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }

    public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
        return $this->show('@root/Admin/prompt', compact('url', 'message', 'time'));
    }
}