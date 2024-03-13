<?php
namespace Module\Bot\Service\Admin;

use Module\Bot\Domain\Model\BotModel;
use Module\Bot\Domain\Repositories\AccountRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ManageController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction(string $keywords = '') {
        $model_list = AccountRepository::getList($keywords);
        $current_id = $this->botId();
        return $this->show(compact('model_list', 'current_id'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = BotModel::findOrNew($id);
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
        $model = BotModel::find($id);
        if (empty($model)) {
            return $this->redirect($this->getUrl('manage'));
        }
        $this->botId($id);
        return $this->redirect($this->getUrl('manage'));
    }
}