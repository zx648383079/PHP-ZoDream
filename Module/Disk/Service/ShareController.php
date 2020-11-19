<?php
namespace Module\Disk\Service;

use Module\Auth\Domain\Model\UserModel;
use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Model\ShareFileModel;
use Module\Disk\Domain\Model\ShareModel;
use Module\Disk\Domain\Model\ShareUserModel;


use Zodream\Service\Factory;

class ShareController extends Controller {

    const PASSWORD_KEY = 'share_password';

    protected function rules() {
        return [
            '*' => '*'
        ];
    }
    
    public function indexAction($id) {
        if (app('request')->isPost()) {
            Factory::session()->set(self::PASSWORD_KEY, app('request')->get('password'));
        }
        $model = ShareModel::find($id);
        if (empty($model)) {
            return static::noFound();
        }
        if (auth()->id() == $model->user_id) {
            $user = auth()->user();
            return $this->show(compact('model', 'user'));
        }
        $user = UserModel::find($model->user_id);
        if ($this->canView($model)) {
            return $this->show(compact('model', 'user'));
        }
        if ($model->mode == ShareModel::SHARE_PROTECTED) {
            return $this->show('password', compact('model', 'user'));
        }
        if (auth()->guest()) {
            return $this->redirectWithAuth();
        }
        return $this->redirect('./');
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
        if (empty($model) || !$this->canView($model)) {
            return $this->renderFailure('分享不存在！');
        }
        if (empty($id)) {
            $data = ShareFileModel::alias('s')
                ->leftJoin('disk d', 's.disk_id = d.id')
                ->left('disk_file f', 'd.file_id', 'f.id')
                ->where('s.share_id', $model->id)
                ->select('d.*', 'f.extension', 'f.size')
                ->limit($length)->offset($offset)->asArray()->all();
            return $this->renderData($data);
        }
        $data = DiskModel::alias('d')
            ->where('d.parent_id', $id)
            ->select('d.*', 'f.extension', 'f.size')
            ->limit($length)->offset($offset)->asArray()->all();
        return $this->renderData($data);
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
        $user = auth()->id();
        $models = ShareUserModel::query()->alias('su')
            ->leftJoin('share s', ['su.share_id' => 's.id'])
            ->leftJoin('user u', ['u.id' => 's.user_id'])
            ->where(['su.user_id' => $user])
            ->orderBy('s.create_at desc')
            ->select('s.id as id, s.title as title, s.create_at as create_at, u.id as user_id, u.username as username, u.avatar as avatar')
            ->all();
        return $this->show('me', compact('models'));
    }


    public function myListAction() {
        $models = ShareModel::alias('s')
//            ->leftJoin('disk d', 's.disk_id', 'd.id')
//            ->leftJoin('disk_file f', 'd.file_id', 'f.id')
            ->where('s.user_id', auth()->id())
            ->select('s.name', 's.id', 's.mode', 's.created_at',
                's.view_count', 's.down_count', 's.save_count')->asArray()->all();
        return $this->renderData($models);
    }

    public function cancelAction() {
        $id = app('request')->get('id');
        $row = ShareModel::auth()->whereIn('id', (array)$id)->delete();
        if (empty($row)) {
            return $this->renderFailure('服务器错误!');
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function saveAction($id, $file, $parent = 0) {
        $model = ShareModel::find($id);
        if (!$this->canView($model)) {
            return  $this->renderFailure('无法保存分享!');
        }
        if (auth()->id() == $model->user_id) {
            return  $this->renderFailure('自己分享的不能保存!');
        }
        $disk = null;
        if ($parent > 0 && !($disk = DiskModel::find($parent))) {
            return $this->renderFailure('保存的文件夹不存在！');
        }
        $files = $model->getFile($file);
        DiskModel::saveDiskTo($files, $disk);
        return $this->renderData(null, '保存成功！');
    }

    protected function canView(ShareModel $model) {
        if (auth()->id() == $model->user_id) {
            return true;
        }
        if ($model->mode == ShareModel::SHARE_PUBLIC) {
            return true;
        }
        if ($model->mode == ShareModel::SHARE_PROTECTED) {
            return Factory::session(self::PASSWORD_KEY) == $model->password;
        }
        if (auth()->guest()) {
            return false;
        }
        if ($model->mode == ShareModel::SHARE_PRIVATE) {
            $count = ShareUserModel::where(['share_id' => $model->id, 'user_id' => auth()->id()])->count();
            return $count > 0;
        }
        return true;
    }
}