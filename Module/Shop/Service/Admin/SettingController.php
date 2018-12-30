<?php
namespace Module\Shop\Service\Admin;



use Module\Template\Domain\Model\OptionModel;

class SettingController extends Controller {

    public function indexAction() {
        $group_list = OptionModel::where('parent_id', 0)->orderBy('position', 'asc', 'id', 'asc')->all();
        return $this->show(compact('group_list'));
    }

    public function saveAction() {
        $this->saveNewOption();
        $this->saveOption();
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    private function saveNewOption() {
        $data = app('request')->get('field');
        if (empty($data) || !is_array($data) || !isset($data['name'])) {
            return;
        }
        if (empty($data['name'])) {
            return;
        }
        if (OptionModel::where('name', $data['name'])->count() > 0) {
            return;
        }
        if ($data['type'] == 'group') {
            return OptionModel::create([
                'name' => $data['name'],
                'type' => 'group'
            ]);
        }
        if (empty($data['code']) || $data['parent_id'] < 1) {
            return;
        }
        if (OptionModel::where('code', $data['code'])->count() > 0) {
            return;
        }
        OptionModel::create($data);
    }

    private function saveOption() {
        $data = app('request')->get('option');
        if (empty($data)) {
            return;
        }
        foreach ($data as $id => $value) {
            OptionModel::where('id', $id)->update(compact('value'));
        }
    }
}