<?php
namespace Module\WeChat\Service\Api;

use Module\WeChat\Domain\EmulateResponse;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Model\WeChatSimpleModel;
use Module\WeChat\Module;

class EmulateController extends Controller {

    protected function rules() {
        return [
            '*' => '*'
        ];
    }

    public function indexAction($id = 1) {
        $wx = WeChatSimpleModel::find(intval($id));
        if (empty($wx)) {
            return $this->renderFailure('公众号不存在');
        }
        $data = $wx->toArray();
        $data['menu'] = MenuModel::with('children')->where('wid', $wx->id)->where('parent_id', 0)
            ->get();
        $data['news'] = MediaModel::where('wid', $wx->id)->where('type', MediaModel::TYPE_NEWS)
            ->orderBy('created_at', 'desc')->limit(6)
            ->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb', 'created_at')->get();
        return $this->show($data);
    }

    public function replyAction($id, $content, $type = '') {
        $model = WeChatModel::find($id);
        $reply = Module::reply()->setModel($model);
        $reply->setResponse(new EmulateResponse());
        if ($type === 'menu') {
            $reply->replyMenu($content);
        } else {
            $reply->replyMessage($content);
        }
        return $this->render($reply->getResponse());
    }

    public function mediaAction($id) {
        $model = MediaModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('资源不存在');
        }
        $data = $model->toArray();
        $data['wx'] = WeChatSimpleModel::find($model->wid);
        return $this->show($data);
    }
}