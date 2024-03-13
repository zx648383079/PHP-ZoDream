<?php
declare(strict_types=1);
namespace Module\Bot\Service;

use Module\Bot\Domain\Model\MediaModel;
use Module\Bot\Domain\Model\MenuModel;
use Module\Bot\Domain\Model\BotModel;
use Module\Bot\Domain\Repositories\EmulateRepository;

class EmulateController extends Controller {

    public function indexAction(int $id = 1) {
        $wx = BotModel::find($id);
        if (empty($wx)) {
            return $this->redirect('./');
        }
        $menu_list = MenuModel::with('children')->where('bot_id', $wx->id)->where('parent_id', 0)
            ->get();
        $news_list = MediaModel::where('bot_id', $wx->id)->where('type', MediaModel::TYPE_NEWS)
            ->orderBy('created_at', 'desc')->limit(6)
            ->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb', 'created_at')->get();
        return $this->show(compact('wx', 'menu_list', 'news_list'));
    }

    public function replyAction(int $id) {
        try {
            return $this->renderData(EmulateRepository::reply($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function mediaAction(int $id) {
        $model = MediaModel::find($id);
        if (empty($model)) {
            return $this->redirect('./');
        }
        $wx = BotModel::find($model->bot_id);
        return $this->show(compact('model', 'wx'));
    }
}