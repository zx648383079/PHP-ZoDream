<?php
namespace Service\Api;


class GoodsController extends Controller {

    public function indexAction($page = 1) {
        return $this->success([
            'total' => 900,
            'page' => [
                [
                    'id' => $page
                ]
            ]
        ]);
    }

}