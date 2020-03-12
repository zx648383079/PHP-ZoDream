<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\TemplateModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\ThirdParty\WeChat\Media;

class MediaController extends Controller {

    protected function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction($keywords = null, $type = null) {
        $model_list = MediaModel::where('wid', $this->weChatId())
            ->when(!empty($type), function ($query) use ($type) {
            $query->where('type', $type);
        })->when(!empty($keywords), function ($query) {
            MediaModel::searchWhere($query, 'title');
            })->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb')->page();
        if (app('request')->isAjax() && !app('request')->isPjax()) {
            return $this->jsonSuccess($model_list);
        }
        return $this->show(compact('model_list', 'type'));
    }

    public function createAction($type = null) {
        return $this->runMethodNotProcess('edit', ['id' => null, 'type' => $type]);
    }

    public function editAction($id, $type = null) {
        $model = MediaModel::findOrNew($id);
        if ($id < 1) {
            $model->type = $type === 'media' ? '' : MediaModel::TYPE_NEWS;
        }
        if ($model->type !== MediaModel::TYPE_NEWS) {
            return $this->show('editMedia', compact('model'));
        }
        $model_list = MediaModel::where('wid', $this->weChatId())
            ->where('id', '<>', intval($id))->where('parent_id', 0)
            ->get('id, title');
        return $this->show('edit', compact('model', 'model_list'));
    }

    public function saveAction() {
        $model = new MediaModel();
        $model->wid = $this->weChatId();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('media')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        $model = MediaModel::find($id);
        if ($model->media_id && $model->material_type == MediaModel::MATERIAL_PERMANENT) {
            WeChatModel::find($this->weChatId())
                ->sdk(Media::class)->deleteMedia($model->media_id);
        }
        $model->delete();
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function asyncAction($id) {
        $model = MediaModel::find($id);
        if ($model->media_id &&
            ($model->material_type == MediaModel::MATERIAL_PERMANENT || $model->expired_at > time())) {
            return $this->jsonFailure('不能重复创建');
        }
        if (!$model->async(WeChatModel::find($this->weChatId())
            ->sdk(Media::class))) {
            return $this->jsonFailure('创建失败');
        }
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}