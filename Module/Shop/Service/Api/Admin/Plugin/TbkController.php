<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin\Plugin;

use Module\Shop\Domain\Repositories\Plugin\TbkRepository;
use Module\Shop\Service\Api\Admin\Controller;
use Zodream\Infrastructure\Contracts\Http\Input;

class TbkController extends Controller {

    public function indexAction(string $keywords = '', $page = 1) {
        return $this->renderData(TbkRepository::search($keywords, $page));
    }

    public function optionAction() {
        return $this->render(TbkRepository::option());
    }

    public function saveOptionAction(Input $input) {
        try {
            $data = $input->validate([
                'app_key' => 'required|string',
                'secret' => 'required|string',
            ]);
            return $this->render(
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
                TbkRepository::import($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}