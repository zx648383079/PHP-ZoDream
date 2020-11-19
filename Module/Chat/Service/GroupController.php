<?php
namespace Module\Chat\Service;

use Module\Chat\Domain\Model\GroupModel;
use Module\Chat\Domain\Model\GroupUserModel;

class GroupController extends Controller {

    public function indexAction() {
        $ids = GroupUserModel::where('user_id', auth()->id())->pluck('group_id');
        if (empty($ids)) {
            return $this->renderData([]);
        }
        $data = GroupModel::whereIn('id', $ids)->all();
        return $this->renderData($data);
    }
}