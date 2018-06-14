<?php
namespace Module\LogView\Service;


use Module\LogView\Domain\Model\LogModel;

class AnalysisController extends Controller {


    public function indexAction($name, $value, $type = 'hour') {
        $data = LogModel::countByTime()->where($name, $value)->all();
        return $this->show();
    }

    public function topAction($name) {
        $model = new LogModel();
        if ($model->hasColumn($name)) {
            return $this->redirect('./');
        }
        $top_list = LogModel::groupBy($name)->select($name, 'COUNT(*) as count')
            ->orderBy('count', 'desc')->page();
        return $this->show(compact('top_list'));
    }
}