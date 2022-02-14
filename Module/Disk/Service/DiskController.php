<?php
namespace Module\Disk\Service;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Model\FileModel;
use Module\Disk\Domain\Model\ShareFileModel;
use Module\Disk\Domain\Model\ShareModel;
use Module\Disk\Domain\Model\ShareUserModel;
use Module\Disk\Domain\Repositories\DiskRepository;
use Zodream\Database\Command;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\FileObject;
use Zodream\Disk\FileSystem;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Exception;

class DiskController extends Controller {

    public function rules() {
        return [
            'import' => 'cli',
            '*' => '@'
        ];
    }
    
    public function indexAction() {
        $role = [];
        return $this->show(compact('role'));
    }


    public function listAction($id = 0, $path = '', $type = '') {
        $data = DiskRepository::driver()->catalog($id, $path);
        $data->map(function ($file) {
            if (isset($file['file']) && isset($file['file']['url']) && !empty($file['file']['url'])) {
                $file['file']['url'] = url('./file', ['id' => $file['id']]);
            }
            return $file;
        });
        return $this->renderPage($data);
    }

    public function deleteAction(Request $request) {
        $id = $request->get('id');
        if (empty($id)) {
            return $this->renderFailure('不能为空！');
        }
        $model_list = DiskModel::auth()->where('deleted_at', 0)
            ->whereIn('id', (array)$id)->all();
        foreach ($model_list as $item) {
            $item->softDeleteThis();
        }
        return $this->renderData(true);
    }

    public function shareAction(Request $request) {
        $data = $request->get('id,user,mode 0,end_at');
        if (empty($data['id'])) {
            return $this->renderFailure('不能为空！');
        }
        if (!empty($data['user'])) {
            $data['mode'] = 2;
        }
        $disk = DiskModel::find(current($data['id']));
        $user = auth()->id();
        if ($disk->user_id != $user) {
            return $this->renderFailure('请不要进行危险操作！');
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
            return $this->renderFailure('分享失败');
        }
        $data['url'] = url('./share', ['id' => $model->id]);
        $transaction = app('db')->beginTransaction();
        try {
            $disks = [];
            foreach ((array)$data['id'] as $item) {
                $disks[] = [$item, $model->id];
            }
            ShareFileModel::query()
                ->insert(['disk_id', 'share_id'], $disks);
            if ($model->mode == ShareModel::SHARE_PRIVATE) {
                $users = [];
                foreach ((array)$data['user'] as $item) {
                    $users[] = [$item, $model->id];
                }
                ShareUserModel::query()
                    ->insert(['user_id', 'share_id'], $users);
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
            return $this->renderFailure('分享失败！');
        }
        return $this->renderData($data);
    }

    /**
     * 增加文件夹
     */
    public function createAction(string $name, int $parent_id = 0) {
        try {
            return $this->renderData(DiskRepository::driver()
                ->create($name, $parent_id));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    function renameAction(int $id, string $name) {
        try {
            return $this->renderData(DiskRepository::driver()
                ->rename($id, $name));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    function checkAction(Request $request) {
        $data = $request->get('md5,name,parent_id');
        if (empty($data['md5']) || empty($data['name'])) {
            return $this->renderFailure('不能为空！');
        }
        $model = FileModel::where('md5', $data['md5'])->one();
        if (empty($model)) {
            // 保存文件名等待上传获取
            session()->set('file_'.$data['md5'], $data['name']);
            return $this->renderFailure('MD5 Error', 2);
        }
        $disk = new DiskModel();
        $disk->user_id = auth()->id();
        $disk->file_id = $model->id;
        $disk->name = $model->name;
        $disk->parent_id = intval($data['parent_id']);
        if (!$disk->addAsLast()) {
            return $this->renderFailure($model->getFirstError(), 3);
        }
        $disk->type = $model->type;
        $disk->size = $model->size;
        $disk->url = $this->getUrl($disk);
        return $this->renderData($disk);
    }

    public function addAction(Request $request) {
        $data = $request->get('name,md5,size,parent_id 0,type,temp');
        $file = DiskRepository::driver()->cacheFolder()->file($data['md5']);
        if (!$file->exist() || $file->size() != $data['size']) {
            return $this->renderFailure('FILE ERROR!');
        }
        $data['location'] = md5($data['name'].time()).FileSystem::getExtension($data['name'], true);
        if (!$file->move(DiskRepository::driver()->root()->file($data['location']))) {
            return $this->renderFailure('MOVE FILE ERROR!');
        }
        $fileModel = FileModel::create([
            'name' => $data['name'],
            'extension' => $data['type'],
            'md5' => $data['md5'],
            'location' => $data['location'],
            'size' => $data['size'],
        ]);
        if (empty($fileModel)) {
            return $this->renderFailure('添加失败');
        }
        $model = new DiskModel();
        $model->user_id = auth()->id();
        $model->file_id = $fileModel->id;
        $model->name = $fileModel->name;
        $model->parent_id = $data['parent_id'];
        if (!$model->addAsLast()) {
            return $this->renderFailure($model->getFirstError());
        }
        $model->type = FileModel::getType($data['type']);
        $model->size = $data['size'];
        $model->url = $this->getUrl($model);
        return $this->renderData($model);
    }

    public function folderAction($id) {
        $data = DiskModel::auth()->where('parent_id', $id)->where('file_id', 0)
            ->select('id,name,parent_id')->asArray()->all();
        return $this->renderData($data);
    }

    public function moveAction(Request $request) {
        $id = $request->get('id');
        $parent_id = intval($request->get('parent'));
        if (empty($id)) {
            return $this->renderFailure('没有移动对象');
        }
        $disk = $parent_id > 0 ? DiskModel::find($parent_id) : new DiskModel([
           'id' => $parent_id,
           'left_id' => 0,
           'right_id' => DiskModel::auth()->max('right_id') + 1
        ]);
        if (empty($disk) || $disk->file_id > 0) {
            return $this->renderFailure('移动目标不是文件夹');
        }
        $model_list = DiskModel::auth()->where('deleted_at', 0)
            ->whereIn('id', (array)$id)->all();
        foreach ($model_list as $item) {
            $item->moveTo($disk);
        }
        return $this->renderData('成功');
    }

    public function copyAction(Request $request) {
        $id = $request->get('id');
        $parent_id = intval($request->get('parent'));
        if (empty($id)) {
            return $this->renderFailure('没有移动对象');
        }
        $disk = $parent_id > 0 ? DiskModel::find($parent_id) : new DiskModel([
            'id' => $parent_id,
            'left_id' => 0,
            'right_id' => DiskModel::auth()->max('right_id') + 1
        ]);
        if (empty($disk) || $disk->file_id > 0) {
            return $this->renderFailure('移动目标不是文件夹');
        }
        $model_list = DiskModel::auth()->where('deleted_at', 0)
            ->whereIn('id', (array)$id)->all();
        foreach ($model_list as $item) {
            $item->copyTo($disk);
        }
        return $this->renderData('成功');
    }

    public function usersAction($name = null) {
        $data = UserModel::where('id', '<>', auth()->id())->when(!empty($name), function ($query) {
            SearchModel::searchWhere($query, 'name', false, 'name');
        })->get('name', 'id');
        return $this->renderData($data);
    }

    public function importAction(Request $request) {
       $file = $request->read('', '请输入文件夹：');
       if (empty($file)) {
           return '路径错误';
       }
       $folder = app_path()->directory($file);
       if (!$folder->exist()) {
           return '路径错误';
       }
       $this->importFolder($folder);
        return '导入完成';
    }

    private function importFolder(Directory $directory) {
        $directory->map(function (FileObject $file) {
            if ($file->isDirectory()) {
                $this->importFolder($file);
                return;
            }
            /** @var File $file */
            $fileModel = FileModel::create([
                'name' => $file->getName(),
                'extension' => FileModel::getType($file->getExtension()),
                'md5' => $file->md5(),
                'location' => $file->getFullName(),
                'size' => $file->size(),
            ]);
            if (empty($fileModel)) {
                return '';
            }
            $model = new DiskModel();
            $model->user_id = auth()->id();
            $model->file_id = $fileModel->id;
            $model->name = $fileModel->name;
            $model->parent_id = 0;
            $model->addAsLast();
        });
    }

    public function getUrl($file) {
        if ($file['type'] == FileModel::TYPE_MUSIC) {
            return url('./file/music', ['id' => $file['id']]);
        }
        if ($file['type'] == FileModel::TYPE_VIDEO
            || $file['type'] == FileModel::TYPE_APP) {
            return url('./file', ['id' => $file['id']]);
        }
        return '';
    }
}