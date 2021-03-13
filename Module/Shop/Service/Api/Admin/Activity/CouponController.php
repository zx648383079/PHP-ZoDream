<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin\Activity;

use Module\Shop\Domain\Repositories\Admin\CouponRepository;
use Module\Shop\Service\Api\Admin\Controller;
use Zodream\Infrastructure\Contracts\Http\Input;

class CouponController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            CouponRepository::getList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                CouponRepository::get($id)
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
                'thumb' => 'string:0,255',
                'type' => 'int:0,99',
                'rule' => 'int:0,99',
                'rule_value' => '',
                'min_money' => '',
                'money' => '',
                'send_type' => 'int',
                'send_value' => 'int',
                'every_amount' => 'int',
                'start_at' => '',
                'end_at' => '',
            ]);
            return $this->render(
                CouponRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            CouponRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}