<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Adapters;

use Exception;
use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Model\FileModel;
use Zodream\Disk\FileSystem;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Html\Page;

class Database extends BaseDiskAdapter implements IDiskAdapter {

    public function catalog($id, $path): Page {
        return DiskModel::with('file')
            ->auth()->where('parent_id', $id)
            ->where('deleted_at', 0)
            ->page();
    }

    public function remove($id) {
        $model_list = DiskModel::auth()->where('deleted_at', 0)
            ->whereIn('id', (array)$id)->get();
        foreach ($model_list as $item) {
            $item->softDeleteThis();
        }
    }

    public function create(string $name, $parentId = '')
    {
        if (empty($name)) {
            throw new \Exception('请输入文件夹名');
        }
        $model = new DiskModel();
        $model->name = $name;
        $model->parent_id = intval($parentId);
        $model->created_at = $model->updated_at = time();
        $model->user_id = auth()->id();
        $model->file_id = 0;
        if ($model->isSameName()) {
            throw new Exception('文件已重名');
        }
        if (!$model->addAsLast()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public function upload(BaseUpload $file, string $md5) {
        set_time_limit(0);
        $name = cache(sprintf('file_%s_%s', $md5, auth()->id()));
        if (empty($name)) {
            $name = $md5;
        }
        $result = $file->setName($name)
            ->setFile($this->cacheFolder()->file($md5))
            ->save();
        if (!$result) {
            throw new Exception($file->getError().'');
        }
        return [
            'name' => $file->getName(),
            'size' => $file->getSize(),
            'type' => $file->getType()
        ];
    }

    public function uploadChunk(array $fileData, string $md5) {
        set_time_limit(0);
        $file = $this->cacheFolder()->file($md5);
        $file->append(file_get_contents($fileData['tmp_name']));
        $name = cache(sprintf('file_%s_%s', $md5, auth()->id()));
        if (empty($name)) {
            $name = $md5;
        }
        return [
            'name' => $name,
            'size' => $file->size(),
            'type' => FileSystem::getExtension($name)
        ];
    }

    public function uploadFinish(string $md5, string $name, $parentId = '') {
        $file = $this->cacheFolder()->file($md5);
        if (!$file->exist() || $file->md5() !== $md5) {
            throw new Exception('uploaded file error');
        }
        $size = $file->size();
        $location = md5($name.time()).FileSystem::getExtension($name, true);
        if (!$file->move($this->root()->file($location))) {
            throw new Exception('MOVE FILE ERROR!');
        }
        $fileModel = FileModel::createOrThrow([
            'name' => $name,
            'extension' => FileSystem::getExtension($name),
            'md5' => $md5,
            'location' => $location,
            'size' => $size,
        ], '添加失败');
        $model = new DiskModel();
        $model->user_id = auth()->id();
        $model->file_id = $fileModel->id;
        $model->name = $fileModel->name;
        $model->parent_id = $parentId;
        if (!$model->addAsLast()) {
            throw new Exception($model->getFirstError());
        }
        $model->file = $fileModel;
        return $model->toArray();
    }

    public function uploadCheck(string $md5, string $name, $parentId = ''): array {
        if (empty($md5) || empty($name)) {
            throw new \Exception('不能为空！');
        }
        $model = FileModel::where('md5', $md5)->first();
        if (empty($model)) {
            // 保存文件名等待上传获取
            cache()->set(sprintf('file_%s_%s', $md5, auth()->id()), $name, 86400);
            return [
                'code' => 2,
                'message' => 'MD5 Error'
            ];
        }
        $disk = new DiskModel();
        $disk->user_id = auth()->id();
        $disk->file_id = $model->id;
        $disk->name = $model->name;
        $disk->parent_id = intval($parentId);
        if (!$disk->addAsLast()) {
            throw new \Exception($model->getFirstError());
        }
        $disk->file = $model;
        return [
            'code' => 200,
            'data' => $disk->toArray()
        ];
    }

    public function rename($id, string $name)
    {
        $model = DiskModel::find($id);
        if (empty($model)) {
            throw new \Exception('选择错误的文件！');
        }
        $model->name = $name;
        $model->updated_at = time();
        if ($model->isSameName()) {
            throw new \Exception('文件已重名');
        }
        if (!$model->save()) {
            throw new \Exception('修改失败！');
        }
        return $model;
    }

    public function copy(array $data)
    {
        // TODO: Implement copy() method.
    }

    public function move(array $data)
    {
        // TODO: Implement move() method.
    }

    public function file($id): array
    {
        $model = DiskModel::find($id);
        if (empty($model)) {
            throw new \Exception('文件不存在');
        }
        if ($model->file_id < 1) {
            throw new \Exception('文件夹不能下载');
        }
        $file = $this->root()->file($model->file->location);
        if (!$file->exist()) {
            throw new \Exception('文件不存在');
        }
        $data = array_merge($this->formatFile($model->file, $model->id), [
            'id' => $id,
            'name' => $model->name,
            'path' => $file,
        ]);
        return $data;
    }

    private function formatFile(FileModel $item, $fileId) {
        $data = [
            'size' => $item->size,
            'extension' => strtolower($item->extension),
            'type' => $item->type,
        ];
        if ($data['type'] === FileModel::TYPE_IMAGE) {
            $data['thumb'] = $data['url'] = url('./file/image', ['id' => $fileId]);
        } elseif ($data['type'] === FileModel::TYPE_VIDEO) {
            $data['thumb'] = url('./file/thumb', ['id' => $fileId]);
            $data['url'] = url('./file/m3u8', ['id' => $fileId]);
        }
        return $data;
    }
}