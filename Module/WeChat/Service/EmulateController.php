<?php
namespace Module\WeChat\Service;

use Module\WeChat\Domain\EmulateResponse;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Module;

class EmulateController extends Controller {

    public function indexAction(int $id = 1) {
        $wx = WeChatModel::find($id);
        if (empty($wx)) {
            return $this->redirect('./');
        }
        $menu_list = MenuModel::with('children')->where('wid', $wx->id)->where('parent_id', 0)
            ->get();
        $news_list = MediaModel::where('wid', $wx->id)->where('type', MediaModel::TYPE_NEWS)
            ->orderBy('created_at', 'desc')->limit(6)
            ->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb', 'created_at')->get();
        return $this->show(compact('wx', 'menu_list', 'news_list'));
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
        return $this->renderData($reply->getResponse());
    }

    public function mediaAction($id) {
        $model = MediaModel::find($id);
        if (empty($model)) {
            return $this->redirect('./');
        }
        $wx = WeChatModel::find($model->wid);
        return $this->show(compact('model', 'wx'));
    }
}