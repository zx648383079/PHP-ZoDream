<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Repositories\AccountRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ManageController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction(string $keywords = '') {
        $model_list = AccountRepository::getList($keywords);
        $current_id = $this->weChatId();
        return $this->show(compact('model_list', 'current_id'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = WeChatModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction(Input $input) {
        try {
            AccountRepository::save($input->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('manage')
        ]);
    }

    public function deleteAction(int $id) {
        AccountRepository::remove($id);
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function changeAction($id) {
        $model = WeChatModel::find($id);
        if (empty($model)) {
            return $this->redirect($this->getUrl('manage'));
        }
        $this->weChatId($id);
        return $this->redirect($this->getUrl('manage'));
    }
}