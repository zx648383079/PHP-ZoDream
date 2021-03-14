<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Repositories\ActivityRepository;
use Module\Shop\Service\Api\Controller;

class SeckillController extends Controller {

    public function indexAction(int $id = 0) {
        if (!is_array($id) && $id > 0) {
            return $this->infoAction($id);
        }
        return $this->renderPage(
            ActivityRepository::getList(ActivityModel::TYPE_SEC_KILL)
        );
    }

    public function infoAction(int $id) {
        try {
            return $this->render(
                ActivityRepository::get(ActivityModel::TYPE_SEC_KILL, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function goodsAction(int $act_id = 0, int $time_id = 0, string $time = '') {
        return $this->renderPage(
            ActivityRepository::secKillGoodsList($act_id, $time_id, $time)
        );
    }

    public function timeAction() {
        return $this->renderData(
            ActivityRepository::timeList()
        );
    }
}