<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Models\AttributeUniqueModel;
use Module\Shop\Domain\Models\GoodsAttributeModel;
use Module\Shop\Domain\Models\GoodsGalleryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\ProductModel;
use Module\Shop\Domain\Repositories\GoodsRepository;
use Zodream\Helpers\Json;

class GoodsController extends Controller {

    public function indexAction($id = 0,
                                $category = 0,
                                $brand = 0,
                                $keywords = '',

                                $per_page = 20, $sort = '', $order = '', $trash = false) {
        if (!is_array($id) && $id > 0) {
            return $this->detailAction($id);
        }
        $page = GoodsRepository::searchComplete(!is_array($id) ? [] : $id,
            $category, $brand, $keywords, $per_page, $sort, $order, $trash);
        return $this->renderPage($page);
    }

    public function detailAction($id) {
        $goods = GoodsModel::find($id);
        if (empty($goods)) {
            return $this->renderFailure('商品错误！');
        }
        return $this->render($goods);
    }

    public function saveAction($id, $product = null, $gallery = null, $attr = null) {
        $model = new GoodsModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        if ($id < 1) {
            GoodsAttributeModel::where('goods_id', '<', 1)->update([
                'goods_id' => $model->id
            ]);
        }
        if (!empty($product)) {
            ProductModel::batchSave(is_array($product) ? $product : Json::decode($product), $model->id);
        }
        if (!empty($gallery)) {
            GoodsGalleryModel::batchSave($gallery, $model->id);
        }
        if (!empty($attr)) {
            AttributeUniqueModel::batchSave($model, $attr);
        }
        return $this->render($model);
    }

    public function deleteAction($id, $trash = false) {
        if ($trash) {
            GoodsModel::where('deleted_at', '>', 0)
                ->where('id', $id)->delete();
        } else {
            GoodsModel::where('id', $id)->update([
                'deleted_at' => time()
            ]);
        }
        return $this->renderData(true);
    }

    public function clearAction() {
        GoodsModel::where('deleted_at', '>', 0)->delete();
        return $this->renderData(true);
    }

    public function restoreAction($id = 0) {
        GoodsModel::where('deleted_at', '>', 0)->when($id > 0, function ($query) use ($id) {
            $query->where('id', intval($id));
        })->update([
            'deleted_at' => 0
        ]);
        return $this->renderData(true);
    }
}