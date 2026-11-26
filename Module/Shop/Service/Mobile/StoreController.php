<?php
declare(strict_types=1);
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\GoodsModel;

class StoreController extends Controller {

    public function indexAction() {
        $new_list = GoodsModel::where('is_new', 1)->take(8)->get();
        return $this->show(compact('new_list'));
    }
}