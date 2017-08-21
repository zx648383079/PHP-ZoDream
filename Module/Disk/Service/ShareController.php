<?php
namespace Module\Disk\Service;

use Module\Auth\Domain\Model\UserModel;
use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Model\ShareFileModel;
use Module\Disk\Domain\Model\ShareModel;
use Module\Disk\Domain\Model\ShareUserModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Factory;

class ShareController extends Controller {
    
    public function indexAction($id) {
        $model = ShareModel::find($id);
        if (empty($model)) {
            return static::noFound();
        }
        if (Auth::id() != $model->user_id
            &&  $model->mode != 'public') {
            if ($model->mode == 'protected') {
                if (Request::isPost()) {
                    Factory::session()->set('sharePassword', Request::post('password'));
                }
                if (Factory::session('sharePassword') != $model->password) {
                    return $this->show('password');
                }
            } elseif (Auth::guest()) {
                return $this->show('error');
            } elseif ($model->mode == 'private') {
                $user = Auth::id();
                $count = ShareUserModel::where(['share_id' => $model->id, 'user_id' => $user])->count();
                if ($count < 0) {
                    return $this->show('error');
                }
            } else {

            }
        }
        $user = UserModel::find($model->user_id);
        return $this->show('index', compact('model', 'user'));
    }

    public function allAction() {
        $models = ShareModel::alias('s')
            ->leftJoin('user u', ['u.id' => 's.user_id'])
            ->where(['s.mode' => 'public'])
            ->orderBy('s.create_at desc')
            ->select('s.id as id, s.title as title, s.create_at as create_at, u.id as user_id, u.username as username, u.avatar as avatar')
            ->asArray()->all();
        return $this->show('all', [
            'models' => $models
        ]);
    }

    public function listAction($share, $id = 0, $offset = 0, $length = 20) {
        $model = ShareModel::find($share);
        if (empty($model)) {
            return $this->jsonFailure('分享不存在！');
        }
        if (empty($id)) {
            $data = ShareFileModel::alias('s')
                ->leftJoin('disk d', 's.disk_id = d.id')
                ->where(['s.share_id' => $model->id])
                ->select('d.*')->limit($length)->offset($offset)->asArray()->all();
            return $this->jsonSuccess($data);
        }
        $data = DiskModel::where(['parent_id' => $id])
            ->limit($length)->offset($offset)->asArray()->all();
        return $this->jsonSuccess($data);
    }

    public function userAction($id) {
        $user = UserModel::find($id);
        $models = ShareModel::all(['user_id' => $id, 'mode' => 'public']);
        return $this->show('user', compact('user', 'models'));
    }

    public function myAction() {
        return $this->show('my');
    }

    public function meAction() {
        $user = Auth::id();
        $models = ShareUserModel::alisa('su')
            ->leftJoin('zd_share s', ['su.share_id' => 's.id'])
            ->leftJoin('zd_user u', ['u.id' => 's.user_id'])
            ->where(['su.user_id' => $user])
            ->orderBy('s.create_at desc')
            ->select('s.id as id, s.title as title, s.create_at as create_at, u.id as user_id, u.username as username, u.avatar as avatar')
            ->all();
        return $this->show('me', compact('models'));
    }


    public function myListAction() {
        $user = Auth::id();
        $models = ShareModel::where(['user_id' => $user])->asArray()->all();
        return $this->jsonSuccess($models);
    }

    public function cancelAction() {
        $user = Auth::id();
        $id = Request::post('id');
        $row = ShareModel::where([
                'id' => ['in', (array)$id],
                'user_id' => $user
            ])->delete();
        if (empty($row)) {
            return $this->jsonFailure('服务器错误!');
        }
        return $this->jsonSuccess();
    }
}