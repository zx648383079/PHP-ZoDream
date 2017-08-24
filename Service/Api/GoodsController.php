<?php
namespace Service\Api;


class GoodsController extends Controller {

    protected function format()
    {
        return 'xml';
    }

    public function indexAction($page = 1) {
        return $this->success([
            'total' => 900,
            'pageSize' => 20,
            'page' => $page,
            'pagelist' => [
                [
                    'id' => $page
                ]
            ]
        ]);
    }

}