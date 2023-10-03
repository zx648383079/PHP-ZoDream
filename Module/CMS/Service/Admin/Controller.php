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

    protected File|string $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => CMSRepository::MANAGE_ROLE
        ];
    }

    public function prepare() {
        CMSRepository::resetSite();
        try {
            $currentSite = CMSRepository::site();
        } catch (\Exception $ex) {
            $currentSite = null;
        }
        $cat_menu = empty($currentSite) ? [] : CategoryModel::tree()->makeTreeForHtml();
        $cat_menu = array_filter($cat_menu, function ($item) {
            return $item['type'] < 1;
        });
        $model_menu = ModelModel::query()->orderBy('position', 'asc')
            ->orderBy('id', 'asc')->get('id', 'name', 'type');
        $this->send(compact('cat_menu', 'model_menu', 'currentSite'));
    }

    protected function getUrl(mixed $path, array $args = []): string {
        return url('./@admin/'.$path, $args);
    }

}