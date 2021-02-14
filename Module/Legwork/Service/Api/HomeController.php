<?php
namespace Module\Legwork\Service\Api;

use Module\Legwork\Domain\Model\ServiceModel;

class HomeController extends Controller {

    public function indexAction($category = 0) {
        $data = ServiceModel::where('cat_id', $category)
            ->orderBy('id', 'asc')
            ->get();
        return $this->render(compact('data'));
    }
}