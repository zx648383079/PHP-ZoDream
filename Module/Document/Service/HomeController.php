<?php
namespace Module\Document\Service;

use Module\Document\Domain\Model\ProjectModel;

class HomeController extends Controller {

    public function indexAction() {
        $project_list = ProjectModel::select('name', 'id')
            ->where('status', ProjectModel::STATUS_PUBLIC)->all();
        return $this->show(compact('project_list'));
    }
}