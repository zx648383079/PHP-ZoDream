<?php
namespace Module\Shop\Service\Admin;


use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\AttributeGroupModel;
use Module\Shop\Domain\Models\AttributeModel;
use Module\Shop\Domain\Models\AttributeUniqueModel;
use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\CollectModel;
use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Models\GoodsAttributeModel;
use Module\Shop\Domain\Models\GoodsCardModel;
use Module\Shop\Domain\Models\GoodsGalleryModel;
use Module\Shop\Domain\Models\GoodsIssueModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\ProductModel;
use Module\Shop\Domain\Repositories\Admin\GoodsRepository;
use Zodream\Helpers\Json;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class GoodsController extends Controller {

    public function indexAction($keywords = null, $sort = null, $cat_id = 0, $brand_id = 0, $trash = false) {
        $model_list = GoodsModel::with('category', 'brand')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->when(!empty($cat_id), function ($query) use ($cat_id) {
                $query->where('cat_id', intval($cat_id));
            })->when(!empty($brand_id), function ($query) use ($brand_id) {
                $query->where('brand_id', intval($brand_id));
            })->when(!empty($sort), function ($query) use ($sort) {
                if (in_array($sort, ['is_best', 'is_hot', 'is_new'])) {
                    $query->where($sort, 1);
                }
            })->when($trash, function ($query) {
                $query->where('deleted_at', '>', 0);
            }, function ($query) {
                $query->where('deleted_at', 0);
            })->orderBy('id', 'desc')->page();
        $cat_list = CategoryModel::tree()->makeTreeForHtml();
        $brand_list = BrandModel::select('id', 'name')->all();
        return $this->show(compact('model_list', 'cat_list', 'brand_list', 'keywords', 'cat_id', 'brand_id', 'sort'));
    }

    public function trashAction($keywords = null, $cat_id = 0, $brand_id = 0) {
        return $this->indexAction($keywords, $cat_id, $brand_id, true);
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = GoodsModel::findOrDefault($id, [
            'stock' => 1, 'weight' => 0, 'status' => GoodsModel::STATUS_SALE]);
        $cat_list = CategoryModel::tree()->makeTreeForHtml();
        $brand_list = BrandModel::select('id', 'name')->all();
        $group_list = AttributeGroupModel::all();
        $gallery_list = GoodsGalleryModel::where('goods_id', $id)->all();
        return $this->show('edit', compact('model', 'cat_list', 'brand_list', 'group_list', 'gallery_list'));
    }

    public function saveAction(Input $input) {
        try {
            GoodsRepository::save($input->all());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('goods')
        ]);
    }

    public function deleteAction(int $id, bool $trash = false) {
        GoodsRepository::remove($id, $trash);
        return $this->renderData([
            'url' => $this->getUrl('goods')
        ]);
    }

    public function clearAction() {
        GoodsRepository::clearTrash();
        return $this->renderData([
            'url' => $this->getUrl('goods')
        ]);
    }

    public function restoreAction(int $id = 0) {
        GoodsRepository::restoreTrash($id);
        return $this->renderData([
            'url' => $this->getUrl('goods')
        ]);
    }

    public function toggleAction(int $id, string $name) {
        try {
            GoodsRepository::goodsAction($id, [$name]);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function generateSnAction() {
        return $this->renderData(GoodsRepository::generateSn());
    }

    public function attributeAction(int $group_id, int $goods_id = 0) {
        return $this->renderData(
            GoodsRepository::attributeList($group_id, $goods_id)
        );
    }

    public function saveAttributeAction() {
        $model = new GoodsAttributeModel();
        if (!$model->load()) {
            return $this->renderFailure($model->getFirstError());
        }
        $model->autoIsNew();
        if (!$model->checkValue()) {
            return $this->renderFailure('属性值已存在！');
        }
        if (!$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData($model->toArray());
    }

    public function editAttributeAction($attr, $goods_id = 0) {
        $models = GoodsAttributeModel::batchSave($attr, $goods_id);
        if (empty($models)) {
            return $this->renderFailure('更新失败！');
        }
        return $this->renderData(count($models) == 1 ? reset($models) : $models);
    }

    public function deleteAttributeAction($attribute_id = 0, $value = 0, $goods_id = 0, $id = 0) {
        if ($id > 0) {
            GoodsAttributeModel::where('id', $id)->delete();
        } else {
            GoodsAttributeModel::where('goods_id', $goods_id)->where('attribute_id', $attribute_id)->where('value', $value)->delete();
        }
        return $this->renderData(true);
    }

    public function cardAction(int $id, string $keywords = '') {
        $model = GoodsModel::find($id);
        if (empty($model)) {
            return $this->redirect($this->getUrl('goods'));
        }
        $card_list = GoodsRepository::cardList($id, $keywords);
        return $this->show(compact('card_list', 'model', 'keywords'));
    }

    public function importCardAction($id) {

    }

    public function exportCardAction($id) {

    }

    public function createCardAction(int $id, int $amount = 1) {
        GoodsRepository::cardGenerate($id, $amount);
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function deleteCardAction(int $id) {
        GoodsRepository::cardRemove($id);
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function refreshAction() {
        GoodsRepository::sortOut();
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function dialogAction(
        $keywords = null,
        $cat_id = 0,
        $brand_id = 0, $selected = [],
        $just_selected = false,
        $simple = false) {
        $selected = ModelHelper::parseArrInt($selected);
        $simple = !!$simple;
        $model_list = GoodsSimpleModel::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->when(!empty($cat_id), function ($query) use ($cat_id) {
                $query->where('cat_id', intval($cat_id));
            })->when(!empty($brand_id), function ($query) use ($brand_id) {
                $query->where('brand_id', intval($brand_id));
            })->when($just_selected, function ($query) use ($selected) {
            $query->whereIn('id', $selected);
            })->orderBy('id', 'desc')->page();
        if (request()->wantsJson()) {
            return $this->renderData($model_list);
        }
        if (!$simple) {
            $cat_list = CategoryModel::tree()->makeTreeForHtml();
            $brand_list = BrandModel::select('id', 'name')->all();
            $this->send(compact('cat_id', 'cat_list', 'brand_id', 'brand_list', 'just_selected', 'keywords'));
        }
        $this->layout = false;
        return $this->show(compact('model_list', 'selected', 'simple'));
    }

    public function importAction(Request $request) {
        if ($request->isJson()) {
            GoodsRepository::importJson($request->get());
        }
        return $this->renderData(true);
    }
}