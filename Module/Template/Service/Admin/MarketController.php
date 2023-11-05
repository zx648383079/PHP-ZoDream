<?php
declare(strict_types=1);
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\ThemeComponentModel;
use Module\Template\Domain\Repositories\CategoryRepository;
use Module\Template\Domain\Repositories\ComponentRepository;
use Module\Template\Domain\Repositories\SiteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MarketController extends Controller {
    public function indexAction(string $keywords = '', int $user = 0, int $category = 0,
                                string $sort = 'created_at',
                                string|int|bool $order = 'desc') {
        $items = ComponentRepository::getList($keywords, $category, $user, $sort, $order);
        $cat_list = CategoryRepository::all();
        return $this->show(compact('items', 'category', 'cat_list', 'keywords'));
    }

    public function siteAction(string $keywords = '', int $user = 0,
                               string $sort = 'created_at',
                               string|int|bool $order = 'desc') {
        $items = SiteRepository::getShareList($keywords, $user, $sort, $order);
        return $this->show(compact('items', 'keywords'));
    }

    public function myAction(string $keywords = '', int $type = 0, int $category = 0) {
        $items = ComponentRepository::selfList($keywords, $type, $category);
        $cat_list = CategoryRepository::all();
        $type_list = ['页面', '组件'];
        return $this->show(compact('items', 'category', 'cat_list',
            'keywords', 'type', 'type_list'));
    }

    public function myEditAction(int $id = 0) {
        try {
            $model = empty($id) ? new ThemeComponentModel() : ComponentRepository::selfGet($id);
        } catch (\Exception $ex) {
            return $this->redirectWithMessage($this->getUrl('market/my'), $ex->getMessage());
        }
        $cat_list = CategoryRepository::all();
        return $this->show(compact('model', 'cat_list'));
    }

    public function mySaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
                'description' => 'string:0,200',
                'thumb' => 'string:0,100',
                'cat_id' => 'required|int',
                'price' => 'int',
                'type' => 'int:0,127',
                'author' => 'string:0,20',
                'version' => 'string:0,10',
                'status' => 'int:0,127',
                'path' => 'required|string:0,200',
            ]);
            return $this->render(
                ComponentRepository::selfSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function myDeleteAction(int $id) {
        try {
            ComponentRepository::selfRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }
}