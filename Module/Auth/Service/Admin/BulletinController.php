<?php
namespace Module\Auth\Service\Admin;


use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;

class BulletinController extends Controller {

    public function indexAction($keywords = null) {
        $model_list = BulletinUserModel::with('bulletin')
            ->when(!empty($keywords), function ($query) {
                $ids = SearchModel::searchWhere(BulletinModel::query(), 'title')->pluck('id');
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('bulletin_id', $ids);
            })
            ->where('user_id', auth()->id())
            ->orderBy('status', 'asc')
            ->orderBy('bulletin_id', 'desc')->page();
        return $this->show(compact('model_list', 'keywords'));
    }

    public function createAction() {
        return $this->show();
    }

    public function readAction($id) {
        $model = BulletinUserModel::where('user_id', auth()->id())
            ->where('bulletin_id', $id)->first();
        if (empty($model)) {
            return $this->redirect($this->getUrl('bulletin'));
        }
        BulletinUserModel::where('user_id', auth()->id())
            ->where('bulletin_id', $id)->update([
                'status' => BulletinUserModel::READ,
                'updated_at' => time()
            ]);
        return $this->redirect($this->getUrl('bulletin'));
    }

    public function readAllAction() {
        BulletinUserModel::where('user_id', auth()->id())
            ->where('status', 0)->update([
                'status' => BulletinUserModel::READ,
                'updated_at' => time()
            ]);
        return $this->renderData([
            'refresh' => true
        ]);
    }
}