<?php
namespace Module\Bug\Service;


class HomeController extends Controller {

    public function indexAction() {
        $model_list = BugModel::select('id', 'name', 'grade')->page();
        return $this->show(compact('model_list'));
    }
}