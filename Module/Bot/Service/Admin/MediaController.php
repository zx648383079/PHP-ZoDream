<?php
namespace Module\Bot\Service\Admin;

use Module\Bot\Domain\Model\MediaModel;
use Module\Bot\Domain\Repositories\MediaRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MediaController extends Controller {

    public function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction(string $keywords = '', string $type = '') {
        $model_list = MediaRepository::getList($this->botId(), $keywords, $type);
        if (request()->isAjax() && !request()->isPjax()) {
            return $this->renderData($model_list);
        }
        return $this->show(compact('model_list', 'type'));
    }

    public function createAction($type = null) {
        return $this->editAction(0, $type);
    }

    public function editAction($id, $type = null) {
        $model = MediaModel::findOrNew($id);
        if ($id < 1) {
            $model->type = $type === 'media' ? '' : MediaModel::TYPE_NEWS;
        }
        if ($model->type !== MediaModel::TYPE_NEWS) {
            return $this->show('editMedia', compact('model'));
        }
        $model_list = MediaModel::where('bot_id', $this->botId())
            ->where('id', '<>', intval($id))->where('parent_id', 0)
            ->get('id, title');
        return $this->show('edit', compact('model', 'model_list'));
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->get();
            $data['bot_id'] = $this->botId();
            MediaRepository::save($this->botId(), $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('media')
        ]);
    }

    public function deleteAction(int $id) {
        try {
            MediaRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function asyncAction(int $id) {
        try {
            MediaRepository::async($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }
}