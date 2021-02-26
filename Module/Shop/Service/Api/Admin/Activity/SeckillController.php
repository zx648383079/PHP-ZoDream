<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Repositories\Admin\ActivityRepository;
use Module\Shop\Service\Api\Admin\Controller;
use Zodream\Infrastructure\Contracts\Http\Input;

class SeckillController extends Controller {

    const ACTIVITY_TYPE = ActivityModel::TYPE_SEC_KILL;

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            ActivityRepository::getList(self::ACTIVITY_TYPE, $keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ActivityRepository::get(self::ACTIVITY_TYPE, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,40',
                'thumb' => 'string:0,200',
                'description' => 'string:0,200',
                'start_at' => '',
                'end_at' => '',
            ]);
            $data['scope_type'] = ActivityModel::SCOPE_GOODS;
            return $this->render(
                ActivityRepository::save(self::ACTIVITY_TYPE, $data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ActivityRepository::remove(self::ACTIVITY_TYPE, $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function timeAction() {
        return $this->renderData(
            ActivityRepository::timeList()
        );
    }

    public function detailTimeAction(int $id) {
        try {
            return $this->render(
                ActivityRepository::time($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveTimeAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'title' => 'required|string:0,40',
                'start_at' => '',
                'end_at' => '',
            ]);
            return $this->render(
                ActivityRepository::timeSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteTimeAction(int $id) {
        try {
            ActivityRepository::timeRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function goodsAction(int $act_id, int $time_id) {
        return $this->renderPage(
            ActivityRepository::goodsList($act_id, $time_id)
        );
    }

    public function saveGoodsAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'act_id' => 'required|int',
                'time_id' => 'required|int',
                'goods_id' => 'required|int',
                'price' => '',
                'amount' => 'int',
                'every_amount' => 'int',
            ]);
            return $this->render(
                ActivityRepository::goodsSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteGoodsAction(int $id) {
        try {
            ActivityRepository::goodsRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}