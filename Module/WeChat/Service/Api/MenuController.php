<?php
namespace Module\WeChat\Service\Api;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\Http\Response;
use Zodream\ThirdParty\WeChat\Menu;
use Zodream\ThirdParty\WeChat\MenuItem;

class MenuController extends Controller {


    public function indexAction() {
        $menu_list = MenuModel::with('children')->where('parent_id', 0)->all();
        return $this->renderData($menu_list);
    }

    public function detailAction($id) {
        $model = MenuModel::find($id);
        return $this->render($model);
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
        return $this->render($model);
    }

    public function deleteAction($id) {
        MenuModel::where('id', $id)->delete();
        return $this->renderData(true);
    }

    /**
     * 应用
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
        return $this->renderData(true);
    }
}