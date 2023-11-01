<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Repositories\MediaRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MediaController extends Controller {

    public function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction(string $keywords = '', string $type = '') {
        $model_list = MediaRepository::getList($this->weChatId(), $keywords, $type);
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
        $model_list = MediaModel::where('wid', $this->weChatId())
            ->where('id', '<>', intval($id))->where('parent_id', 0)
            ->get('id, title');
        return $this->show('edit', compact('model', 'model_list'));
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->get();
            $data['wid'] = $this->weChatId();
            MediaRepository::save($data);
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