<?php
namespace Module\Document\Service\Admin;

use Module\Document\Domain\Model\ProjectModel;

class HomeController extends Controller {

    public function indexAction() {
        $project_list = ProjectModel::select('name', 'id')
            ->where('user_id', auth()->id())->all();
        return $this->show(compact('project_list'));
    }
}