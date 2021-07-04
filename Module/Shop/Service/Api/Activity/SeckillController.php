<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Repositories\Activity\ActivityRepository;
use Module\Shop\Domain\Repositories\Activity\SeckillRepository;
use Module\Shop\Service\Api\Controller;

class SeckillController extends Controller {

    public function indexAction(int $id = 0) {
        if (!is_array($id) && $id > 0) {
            return $this->infoAction($id);
        }
        return $this->renderPage(
            SeckillRepository::getList()
        );
    }

    public function infoAction(int $id) {
        try {
            return $this->render(
                SeckillRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function goodsAction(int $act_id = 0, int $time_id = 0, string $time = '') {
        return $this->renderPage(
            SeckillRepository::secKillGoodsList($act_id, $time_id, $time)
        );
    }

    public function timeAction() {
        return $this->renderData(
            ActivityRepository::timeList()
        );
    }
}