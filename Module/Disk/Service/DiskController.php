<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Model\FileModel;
use Module\Disk\Domain\Model\ShareFileModel;
use Module\Disk\Domain\Model\ShareModel;
use Module\Disk\Domain\Model\ShareUserModel;
use Zodream\Database\Command;
use Zodream\Disk\FileSystem;
use Zodream\Domain\Access\Auth;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Factory;
use Zodream\Service\Routing\Url;
use Exception;

class DiskController extends Controller {
    
    public function indexAction() {
        $role = [];
        return $this->show(compact('role'));
    }


    public function listAction($id = 0, $type = null, $offset = 0, $length = 20) {
        if (intval($length) < 1) {
            return $this->jsonFailure('长度不对！');
        }
        $data = DiskModel::auth()
            ->alias('d')
            ->left('disk_file f', 'd.file_id', 'f.id')
            ->when(!empty($type), function ($query) use ($type) {
                return FileModel::searchType($query, $type);
            })
            ->where( 'parent_id', $id)
            ->where('deleted_at', 0)
            ->orderBy('file_id', 'asc')
            ->orderBy('left_id', 'asc')
            ->select('d.*', 'f.extension', 'f.size')
            ->limit($length)->offset($offset)->asArray()->all();
        foreach ($data as &$item) {
            $item['type'] = FileModel::getType($item['extension']);
            $item['url'] = $this->getUrl($item);
        }
        unset($item);
        return $this->jsonSuccess($data);
    }

    public function deleteAction() {
        $id = Request::post('id');
        if (empty($id)) {
            return $this->jsonFailure('不能为空！');
        }
        $model_list = DiskModel::auth()->where('deleted_at', 0)
            ->whereIn('id', (array)$id)->all();
        foreach ($model_list as $item) {
            $item->softDeleteThis();
        }
        return $this->jsonSuccess();
    }

    public function shareAction() {
        $data = Request::post('id,user,mode 0,end_at');
        if (empty($data['id'])) {
            return $this->jsonFailure('不能为空！');
        }
        if (!empty($data['user'])) {
            $data['mode'] = 2;
        }
        $disk = DiskModel::find(current($data['id']));
        $user = Auth::id();
        if ($disk->user_id != $user) {
            return $this->jsonFailure('请不要进行危险操作！');
        }
        $model = new ShareModel();
        $model->name = Str::substr($disk->name, 0, 36).(count($data['id']) > 1 ? '等'.count($data['id']).'个文件' : null);
        $model->mode = intval($data['mode']);
        $model->user_id = $user;
        if ($data['mode'] == 1) {
            $data['password'] = $model->password = Str::random(6);
        }
        if (!empty($data['death_at'])) {
            $model->death_at = time();
        }
        if (!$model->save()) {
            return $this->jsonFailure('分享失败');
        }
        $data['url'] = (string)Url::to('./share', ['id' => $model->id]);
        $transaction = Command::getInstance()->beginTransaction();
        try {
            $disks = [];
            foreach ((array)$data['id'] as $item) {
                $disks[] = [$item, $model->id];
            }
            ShareFileModel::record()
                ->batchInsert(['disk_id', 'share_id'], $disks);
            if ($model->mode == ShareModel::SHARE_PRIVATE) {
                $users = [];
                foreach ((array)$data['user'] as $item) {
                    $users[] = [$item, $model->id];
                }
                ShareUserModel::record()
                    ->batchInsert(['user_id', 'share_id'], $users);
//                Bulletin::message($data['user'],
//                    Yii::$app->user->identity->usrname.'给你分享了文件！',
//                    Html::a('查看', $data['url']));
            }
//            if ($model->mode == 'internal') {
//                Yii::$app->db->createCommand()
//                    ->batchInsert('zd_share_role', ['role_id', 'share_id'], [[$data['role'], $model->id]])
//                    ->execute();
//            }
            $transaction->commit();
        } catch (Exception $ex) {
            $model->delete();
            $transaction->rollBack();
            return $this->jsonFailure('分享失败！');
        }
        return $this->jsonSuccess($data);
    }

    /**
     * 增加文件夹
     */
    public function createAction() {
        $model = new DiskModel();
        $model->name = Request::post('name');
        $model->parent_id = intval(Request::post('parent_id', 0));
        $model->created_at = $model->updated_at = time();
        $model->user_id = Auth::id();
        $model->file_id = 0;
        if (!$model->addAsLast()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess($model->toArray());
    }

    function renameAction() {
        $data = Request::post('id,name');
        $model = DiskModel::find($data['id']);
        if (empty($model)) {
            return $this->jsonFailure('选择错误的文件！');
        }
        $model->name = $data['name'];
        $model->updated_at = time();
        if (!$model->save()) {
            return $this->jsonFailure('修改失败！');
        }
        return $this->jsonSuccess($model);
    }

    function checkAction() {
        $data = Request::post('md5,name,parent_id');
        if (empty($data['md5']) || empty($data['name'])) {
            return $this->jsonFailure('不能为空！');
        }
        $model = FileModel::where('md5', $data['md5'])->one();
        if (empty($model)) {
            // 保存文件名等待上传获取
            Factory::session()->set('file_'.$data['md5'], $data['name']);
            return $this->jsonFailure('MD5 Error', 2);
        }
        $disk = new DiskModel();
        $disk->user_id = Auth::id();
        $disk->file_id = $model->id;
        $disk->name = $model->name;
        $disk->parent_id = intval($data['parent_id']);
        if (!$disk->addAsLast()) {
            return $this->jsonFailure($model->getFirstError(), 3);
        }
        $disk->type = $model->type;
        $disk->size = $model->size;
        $disk->url = $this->getUrl($disk);
        return $this->jsonSuccess($disk);
    }

    public function addAction() {
        $data = Request::post('name,md5,size,parent_id 0,type,temp');
        $file = $this->cacheFolder->file($data['md5']);
        if (!$file->exist() || $file->size() != $data['size']) {
            return $this->jsonFailure('FILE ERROR!');
        }
        $data['location'] = md5($data['name'].time()).FileSystem::getExtension($data['name'], true);
        if (!$file->move($this->diskFolder->file($data['location']))) {
            return $this->jsonFailure('MOVE FILE ERROR!');
        }
        $fileModel = FileModel::create([
            'name' => $data['name'],
            'extension' => $data['type'],
            'md5' => $data['md5'],
            'location' => $data['location'],
            'size' => $data['size'],
        ]);
        if (empty($fileModel)) {
            return $this->jsonFailure('添加失败');
        }
        $model = new DiskModel();
        $model->user_id = Auth::id();
        $model->file_id = $fileModel->id;
        $model->name = $fileModel->name;
        $model->parent_id = $data['parent_id'];
        if (!$model->addAsLast()) {
            return $this->jsonFailure($model->getFirstError());
        }
        $model->type = FileModel::getType($data['type']);
        $model->size = $data['size'];
        $model->url = $this->getUrl($model);
        return $this->jsonSuccess($model);
    }

    public function folderAction($id) {
        $data = DiskModel::auth()->where('parent_id', $id)->where('file_id', 0)
            ->select('id,name,parent_id')->asArray()->all();
        return $this->jsonSuccess($data);
    }

    public function moveAction() {
        $id = Request::post('id');
        $parent_id = intval(Request::post('parent'));
        if (empty($id)) {
            return $this->jsonFailure('没有移动对象');
        }
        $disk = $parent_id > 0 ? DiskModel::find($parent_id) : new DiskModel([
           'id' => $parent_id,
           'left_id' => 0,
           'right_id' => DiskModel::auth()->max('right_id') + 1
        ]);
        if (empty($disk) || $disk->file_id > 0) {
            return $this->jsonFailure('移动目标不是文件夹');
        }
        $model_list = DiskModel::auth()->where('deleted_at', 0)
            ->whereIn('id', (array)$id)->all();
        foreach ($model_list as $item) {
            $item->moveTo($disk);
        }
        return $this->jsonSuccess('成功');
    }

    public function copyAction() {
        $id = Request::post('id');
        $parent_id = intval(Request::post('parent'));
        if (empty($id)) {
            return $this->jsonFailure('没有移动对象');
        }
        $disk = $parent_id > 0 ? DiskModel::find($parent_id) : new DiskModel([
            'id' => $parent_id,
            'left_id' => 0,
            'right_id' => DiskModel::auth()->max('right_id') + 1
        ]);
        if (empty($disk) || $disk->file_id > 0) {
            return $this->jsonFailure('移动目标不是文件夹');
        }
        $model_list = DiskModel::auth()->where('deleted_at', 0)
            ->whereIn('id', (array)$id)->all();
        foreach ($model_list as $item) {
            $item->copyTo($disk);
        }
        return $this->jsonSuccess('成功');
    }

    public function getUrl($file) {
        if ($file['type'] == FileModel::TYPE_MUSIC) {
            return (string)Url::to('./file/music', ['id' => $file['id']]);
        }
        if ($file['type'] == FileModel::TYPE_VIDEO) {
            return (string)Url::to('./file', ['id' => $file['id']]);
        }
        return '';
    }
}