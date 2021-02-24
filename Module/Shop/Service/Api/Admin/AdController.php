<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;


use Module\Shop\Domain\Repositories\Admin\AdRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class AdController extends Controller {

    public function indexAction(string $keywords = '', int|string $position_id = 0) {
        return $this->renderPage(
            AdRepository::getList($keywords, $position_id)
        );
    }


    public function detailAction(int $id) {
        try {
            return $this->render(
                AdRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
                'position_id' => 'required|int',
                'type' => 'int:0,9',
                'url' => 'required|string:0,255',
                'content' => 'required|string:0,255',
                'start_at' => '',
                'end_at' => '',
                'content_url' => '',
            ]);
            return $this->render(
                AdRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            AdRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }


    public function positionAction(string $keywords = '') {
        return $this->renderPage(
            AdRepository::positionList($keywords)
        );
    }

    public function detailPositionAction(int $id) {
        try {
            return $this->render(
                AdRepository::position($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function savePositionAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
                'width' => 'required|string:0,20',
                'height' => 'required|string:0,20',
                'template' => 'required|string:0,255',
            ]);
            return $this->render(
                AdRepository::positionSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deletePositionAction(int $id) {
        try {
            AdRepository::positionRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function positionAllAction() {
        return $this->renderData(
            AdRepository::positionAll()
        );
    }
}