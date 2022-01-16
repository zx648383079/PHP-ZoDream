<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Repositories\MenuRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\ThirdParty\WeChat\Menu;
use Zodream\ThirdParty\WeChat\MenuItem;

class MenuController extends Controller {

    public function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction() {
        $menu_list = MenuRepository::getList($this->weChatId());
        return $this->show(compact('menu_list'));
    }

    public function addAction(int $parent_id = 0) {
        return $this->editAction(0, $parent_id);
    }

    public function editAction(int $id, int $parent_id = 0) {
        $model = MenuModel::findOrNew($id);
        $request = request();
        if ($request->has('type')) {
            $this->layout = false;
            $model->type = $request->get('type');
            return $this->show('/Admin/layouts/editor', compact('model'));
        }
        if ($model->parent_id < $parent_id) {
            $model->parent_id = $parent_id;
        }
        $menu_list = MenuModel::where('parent_id', 0)->all();
        return $this->show('edit', compact('model', 'menu_list'));
    }

    public function saveAction(Request $request) {
        try {
            $data = $request->validate([
                'name' => 'required|string:0,100',
                'type' => 'int:0,127',
                'content' => 'string:0,500',
                'parent_id' => 'int',
            ]);
            MenuRepository::save($this->weChatId(), $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('menu')
        ]);
    }

    public function deleteAction(int $id) {
        MenuRepository::remove($id);
        return $this->renderData([
            'refresh' => true
        ]);
    }

    /**
     * 应用
     * @throws \Exception
     */
    public function applyAction() {
        try {
            MenuRepository::async($this->weChatId());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }
}