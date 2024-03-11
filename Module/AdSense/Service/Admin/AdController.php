<?php
declare(strict_types=1);
namespace Module\AdSense\Service\Admin;

use Module\AdSense\Domain\Entities\AdEntity;
use Module\AdSense\Domain\Entities\AdPositionEntity;
use Module\AdSense\Domain\Repositories\AdRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class AdController extends Controller {

    public function indexAction(string $keywords = '', int $position_id = 0) {
        $model_list = AdRepository::manageList($keywords, $position_id);
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = AdEntity::findOrNew($id);
        $position_list = AdRepository::positionAll();
        return $this->show('edit', compact('model', 'position_list'));
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
                'position_id' => 'required|int',
                'type' => 'int:0,9',
                'url' => 'required|string:0,255',
                'content' => 'string:0,255',
                'start_at' => '',
                'end_at' => '',
                'content_url' => '',
                'status' => 'int:0,127',
            ]);
            AdRepository::manageSave($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('ad')
        ]);
    }

    public function deleteAction(int $id) {
        AdRepository::manageRemove($id);
        return $this->renderData([
            'url' => $this->getUrl('ad')
        ]);
    }


    public function positionAction() {
        $model_list = AdRepository::managePositionList();
        return $this->show(compact('model_list'));
    }

    public function createPositionAction() {
        return $this->editPositionAction(0);
    }

    public function editPositionAction(int $id) {
        $model = AdPositionEntity::findOrNew($id);
        return $this->show('editPosition', compact('model'));
    }

    public function savePositionAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
                'code' => 'required|string:0,20',
                'auto_size' => 'int:0,127',
                'source_type' => 'int:0,127',
                'width' => 'string:0,10',
                'height' => 'string:0,10',
                'template' => 'string:0,500',
                'status' => 'int:0,127',
            ]);
            AdRepository::managePositionSave($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('ad/position')
        ]);
    }

    public function deletePositionAction(int $id) {
        AdRepository::managePositionRemove($id);
        return $this->renderData([
            'url' => $this->getUrl('ad/position')
        ]);
    }
}