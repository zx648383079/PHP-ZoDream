<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\Http\Response;
use Zodream\ThirdParty\WeChat\Menu;
use Zodream\ThirdParty\WeChat\MenuItem;

class MenuController extends Controller {

    protected function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction() {
        $menu_list = MenuModel::where('parent_id', 0)->all();
        return $this->show(compact('menu_list'));
    }

    public function addAction($parent_id = 0) {
        return $this->runMethodNotProcess('edit', ['id' => null, 'parent_id' => $parent_id]);
    }

    public function editAction($id, $parent_id = 0) {
        $model = MenuModel::findOrNew($id);
        $request = app('request');
        if ($request->has('type')) {
            $this->layout = false;
            $model->type = $request->get('type');
            return $this->show('/Admin/layouts/editor', compact('model'));
        }
        if ($model->parent_id < $parent_id) {
            $model->parent_id = $parent_id;
        }
        $menu_list = MenuModel::where('parent_id', 0)->all();
        return $this->show(compact('model', 'menu_list'));
    }

    public function saveAction(Request $request) {
        $model = new MenuModel();
        $model->wid = $this->weChatId();
        try {
            $model->load();
            EditorInput::save($model, $request);
            if (!$model->autoIsNew()->save()) {
                return $this->renderFailure($model->getFirstError());
            }
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('menu')
        ]);
    }

    public function deleteAction($id) {
        MenuModel::where('id', $id)->delete();
        return $this->renderData([
            'refresh' => true
        ]);
    }

    /**
     * 应用
     * @return Response
     * @throws \Exception
     */
    public function applyAction() {
        $menu_list = MenuModel::with('children')->where('wid', $this->weChatId())->all();
        try {
            WeChatModel::find($this->weChatId())
                ->sdk(Menu::class)
                ->create(MenuItem::menu(array_map(function (MenuModel $menu) {
                    return $menu->toMenu();
                }, $menu_list)));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }
}