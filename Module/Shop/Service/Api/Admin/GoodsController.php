<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Models\AttributeModel;
use Module\Shop\Domain\Models\AttributeUniqueModel;
use Module\Shop\Domain\Models\GoodsAttributeModel;
use Module\Shop\Domain\Models\GoodsGalleryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\ProductModel;
use Module\Shop\Domain\Repositories\GoodsRepository;
use Zodream\Helpers\Json;

class GoodsController extends Controller {

    public function indexAction(int $id = 0,
                                int $category = 0,
                                int $brand = 0,
                                string $keywords = '',
                                int $per_page = 20, string $sort = '', string $order = '', bool $trash = false) {
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

    public function toggleAction($id, $name) {
        if ($id < 1 || !in_array($name, ['is_best', 'is_hot', 'is_new'])) {
            return $this->renderFailure('信息错误！');
        }
        GoodsModel::where('id', $id)->updateBool($name);
        return $this->renderData(true);
    }

    public function generateSnAction() {
        $sn = GoodsRepository::generateSn();
        return $this->renderData($sn);
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

    public function attributeAction($group_id, $goods_id = 0) {
        $attr_list = AttributeModel::where('group_id', $group_id)->orderBy('position asc')->orderBy('type asc')->asArray()->all();
        foreach ($attr_list as &$item) {
            $item['default_value'] = empty($item['default_value']) || $item['input_type'] < 1 ? [] : explode(PHP_EOL, trim($item['default_value']));
            $item['attr_items'] = GoodsAttributeModel::where('goods_id', $goods_id)->where('attribute_id', $item['id'])->all();
        }
        unset($item);
        $product_list = ProductModel::where('goods_id', $goods_id)->orderBy('id asc')->all();
        return $this->render(compact('attr_list', 'product_list'));
    }

    public function searchAction(string $keywords = '', int $category = 0, int $brand = 0) {
        return $this->renderPage(GoodsRepository::searchWithProduct($keywords, $category, $brand));
    }
}