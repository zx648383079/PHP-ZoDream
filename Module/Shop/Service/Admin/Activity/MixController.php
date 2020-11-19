<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Service\Admin\Controller;
use Zodream\Infrastructure\Http\Request;

class MixController extends Controller {

    public function indexAction() {
        $model_list = ActivityModel::where('type', ActivityModel::TYPE_MIX)->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ActivityModel::findOrNew($id);
        $configure = $model->mix_configure;
        return $this->show(compact('model', 'configure'));
    }

    public function saveAction(Request $request, $id = 0) {
        $model = ActivityModel::findOrNew($id);
        $model->type = ActivityModel::TYPE_MIX;
        $model->name = $request->get('name');
        $model->description = $request->get('description');
        $model->start_at = $request->get('start_at');
        $model->end_at = $request->get('end_at');
        $goods_list = self::formArr($request->get('configure'), 0);
        if (empty($goods_list)) {
            return $this->renderFailure('请选择商品');
        }
        $model->scope_type = ActivityModel::SCOPE_GOODS;
        $model->scope = array_column($goods_list, 'goods_id');
        $model->configure = [
            'goods' => $goods_list,
            'price' => $request->get('price')
        ];
        if (!$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('activity/mix')
        ], '保存成功！');
    }

    public function deleteAction($id) {
        ActivityModel::where('type', ActivityModel::TYPE_MIX)->where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('activity/mix')
        ]);
    }

}