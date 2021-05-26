<?php
namespace Module\Game\CheckIn\Service\Admin;

use Module\Game\CheckIn\Domain\Repositories\CheckinRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class SettingController extends Controller {

    public function indexAction() {
        $data = CheckinRepository::option();
        return $this->show(compact('data'));
    }

    public function saveAction(Input $input) {
        $data = $input->get('option.checkin');
        $plus = [];
        foreach ($data['day'] as $i => $item) {
            if (!isset($data['plus'][$i]) || intval($data['plus'][$i]) <= 0 || intval($item) <= 0) {
                continue;
            }
            $plus[intval($item)] = intval($data['plus'][$i]);
        }
        CheckinRepository::optionSave(intval($data['basic']), intval($data['loop']), $plus);
        return $this->renderData([
            'refresh' => true
        ]);
    }
}