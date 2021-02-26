<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Repositories\Admin\ActivityRepository;
use Module\Shop\Service\Api\Admin\Controller;
use Zodream\Infrastructure\Contracts\Http\Input;

class FreeTrialController extends Controller {

    const ACTIVITY_TYPE = ActivityModel::TYPE_FREE_TRIAL;

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
                'scope' => 'required|int',
                'configure' => 'required',
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
}