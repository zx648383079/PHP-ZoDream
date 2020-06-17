<?php
namespace Module\Legwork\Service\Api;

use Module\Legwork\Domain\Model\ServiceModel;
use Zodream\Route\Controller\RestController;

class HomeController extends RestController {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($category = 0) {
        $data = ServiceModel::where('cat_id', $category)
            ->orderBy('id', 'asc')
            ->get();
        return $this->render(compact('data'));
    }
}