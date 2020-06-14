<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;
use Zodream\Route\Controller\RestController;

class BulletinController extends RestController {

    protected function rules() {
        return [
            '*' => '@',
        ];
    }

    public function indexAction($keywords = null, $status = 0) {
        $model_list = BulletinUserModel::with('bulletin')
            ->when(!empty($keywords), function ($query) {
                $ids = BulletinModel::where(function ($query) {
                    BulletinModel::search($query, 'title');
                })->pluck('id');
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('bulletin_id', $ids);
            })
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status - 1);
            })
            ->where('user_id', auth()->id())
            ->orderBy('status', 'asc')
            ->orderBy('bulletin_id', 'desc')->page();
        return $this->renderPage($model_list);
    }

    public function infoAction($id) {
        $model = BulletinUserModel::where('user_id', auth()->id())
            ->where('bulletin_id', $id)->first();
        if (empty($model)) {
            return $this->renderFailure('消息不存在');
        }
        $model->status = BulletinUserModel::READ;
        $model->save();
        return $this->render($model);
    }

    public function readAction($id) {
        $model = BulletinUserModel::where('user_id', auth()->id())
            ->where('bulletin_id', $id)->first();
        if (empty($model)) {
            return $this->renderFailure('消息不存在');
        }
        BulletinUserModel::where('user_id', auth()->id())
            ->where('bulletin_id', $id)->update([
                'status' => BulletinUserModel::READ,
                'updated_at' => time()
            ]);
        return $this->render([
            'data' => true
        ]);
    }

    public function readAllAction() {
        BulletinUserModel::where('user_id', auth()->id())
            ->where('status', 0)->update([
                'status' => BulletinUserModel::READ,
                'updated_at' => time()
            ]);
        return $this->render([
            'data' => true
        ]);
    }

    public function deleteAction($id) {
        $model = BulletinUserModel::where('user_id', auth()->id())
            ->where('bulletin_id', $id)->first();
        if (empty($model)) {
            return $this->renderFailure('消息不存在');
        }
        $model->delete();
        return $this->render([
            'data' => true
        ]);
    }

}