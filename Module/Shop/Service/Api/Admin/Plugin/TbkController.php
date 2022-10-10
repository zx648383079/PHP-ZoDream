<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin\Plugin;

use Module\Shop\Domain\Plugin\Tbk\TbkRepository;
use Module\Shop\Service\Api\Admin\Controller;
use Zodream\Infrastructure\Contracts\Http\Input;

class TbkController extends Controller {

    public function indexAction(string $keywords = '', int $page = 1) {
        return $this->renderData(TbkRepository::search($keywords, $page));
    }

    public function optionAction() {
        return $this->renderData(TbkRepository::option());
    }

    public function saveOptionAction(Input $input) {
        try {
            $data = $input->validate([
                'app_key' => 'required|string',
                'secret' => 'required|string',
            ]);
            return $this->renderData(
                TbkRepository::saveOption($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function importAction(Input $input) {
        try {
            $data = $input->validate([
                'adzone_id' => 'required|string',
                'start_time' => 'required|string',
                'end_time' => 'required|string',
            ]);
            return $this->renderData(
                TbkRepository::import($data['adzone_id'], $data['start_time'], $data['end_time'])
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function statisticsAction() {
        return $this->render(TbkRepository::statistics());
    }
}