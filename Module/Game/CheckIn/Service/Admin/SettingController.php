<?php
namespace Module\Game\CheckIn\Service\Admin;

use Module\SEO\Domain\Model\OptionModel;
use Zodream\Helpers\Json;

class SettingController extends Controller {

    public function indexAction() {
        $data = OptionModel::findCodeJson('checkin', [
            'basic' => 1,
            'loop' => 0,
            'plus' => []
        ]);
        return $this->show(compact('data'));
    }

    public function saveAction() {
        $data = app('request')->get('option.checkin');
        $plus = [];
        foreach ($data['day'] as $i => $item) {
            if (!isset($data['plus'][$i]) || intval($data['plus'][$i]) <= 0 || intval($item) <= 0) {
                continue;
            }
            $plus[intval($item)] = intval($data['plus'][$i]);
        }
        ksort($plus);
        OptionModel::insertOrUpdate('checkin', Json::encode([
            'basic' => intval($data['basic']),
            'loop' => intval($data['loop']),
            'plus' => $plus
        ]), '签到');
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}