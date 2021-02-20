<?php
namespace Module\WeChat\Service\Api;

use Domain\Model\SearchModel;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\TemplateModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\ThirdParty\WeChat\Media;

class MediaController extends Controller {


    public function indexAction($keywords = null, $type = null) {
        $model_list = MediaModel::where('wid', $this->weChatId())
            ->when(!empty($type), function ($query) use ($type) {
            $query->where('type', $type);
        })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
        })->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb')->page();
        return $this->renderPage($model_list);
    }

    public function detailAction($id) {
        $model = MediaModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new MediaModel();
        $model->wid = $this->weChatId();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        $model = MediaModel::find($id);
        if ($model->media_id && $model->material_type == MediaModel::MATERIAL_PERMANENT) {
            WeChatModel::find($this->weChatId())
                ->sdk(Media::class)->deleteMedia($model->media_id);
        }
        $model->delete();
        return $this->renderData(true);
    }

    public function asyncAction($id) {
        $model = MediaModel::find($id);
        if ($model->media_id &&
            ($model->material_type == MediaModel::MATERIAL_PERMANENT || $model->expired_at > time())) {
            return $this->renderFailure('不能重复创建');
        }
        if (!$model->async(WeChatModel::find($this->weChatId())
            ->sdk(Media::class))) {
            return $this->renderFailure('创建失败');
        }
        return $this->renderData(true);
    }
}