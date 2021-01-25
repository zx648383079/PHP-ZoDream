<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Adapters;

use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Model\FileModel;
use Zodream\Disk\File;
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

    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    public function upload($file)
    {
        // TODO: Implement upload() method.
    }

    public function checkMd5(array $data)
    {
        // TODO: Implement checkMd5() method.
    }

    public function rename(array $data)
    {
        // TODO: Implement rename() method.
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