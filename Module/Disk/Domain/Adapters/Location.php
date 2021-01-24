<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Adapters;

use Module\Disk\Domain\Model\FileModel;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\FileObject;
use Zodream\Helpers\Time;
use Zodream\Html\Page;

class Location extends BaseDiskAdapter implements IDiskAdapter {

    public function catalog($id, $path): Page {
        $root = $this->root();
        $folder = empty($id) ? $root : $root->directory($id);
        $items = $folder->children();
        $page = new Page($items);
        $items = [];
        foreach ($page as $item) {
            /** @var $item FileObject */
            $fileId = $item->getRelative($root);
            $items[] = [
                'id' => $fileId,
                'name' => $item->getName(),
                'file_id' => $item instanceof Directory ? 0 : 1,
                'created_at' => Time::format($item instanceof Directory ? null : $item->createTime()),
                'updated_at' => Time::format($item instanceof Directory ? null : $item->modifyTime()),
                'file' => $item instanceof Directory ? null : $this->formatFile($item, $fileId)
            ];
        }
        return $page->setPage($items);
    }

    private function formatFile(File $item, $fileId) {
        $data = [
            'size' => $item->size(),
            'extension' => strtolower($item->getExtension()),
        ];
        $data['type'] = FileModel::getType($data['extension']);
        if ($data['type'] === FileModel::TYPE_IMAGE) {
            $data['thumb'] = $data['url'] = url('./file/image', ['id' => $fileId]);
        } elseif ($data['type'] === FileModel::TYPE_VIDEO) {
            $data['thumb'] = url('./file/thumb', ['id' => $fileId]);
            $data['url'] = url('./file/m3u8', ['id' => $fileId]);
        }
        return $data;
    }

    public function remove($id) {

    }

    protected function getRealFolder($path) {
        if (!empty($path) && is_dir($path)) {
            return new Directory($path);
        }
        return parent::getRealFolder($path);
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

    public function file($id)
    {
        $root = $this->root();
        $file = $root->file($id);
        if (!$file->exist()) {
            throw new \Exception('文件不存在');
        }
        $ext = strtolower($file->getExtension());
        $data = [
            'id' => $id,
            'name' => $file->getName(),
            'size' => $file->size(),
            'extension' => $ext,
            'type' => FileModel::getType($ext),
            'path' => $file,
        ];
        if ($data['type'] === FileModel::TYPE_VIDEO) {
            $subtitles = $this->getSubtitles($file);
            if (!empty($subtitles)) {
                $data['subtitles'] = array_map(function (File $item) use ($root) {
                    return [
                        'id' => $item->getRelative($root),
                        'lang' => 'zh-cn',
                        'label' => '简体中文',
                    ];
                }, $subtitles);
            }
        }
        return $data;
    }

    /**
     * 获取字幕
     * @param File $file
     * @return array
     */
    protected function getSubtitles(File $file) {
        $extItems = config('disk.subtitles');
        if (empty($extItems)) {
            return [];
        }
        if (!is_array($extItems)) {
            $extItems = explode('|', $extItems);
        }
        $items = [];
        $name = $file->getNameWithoutExtension();
        $file->getDirectory()->map(function ($item) use (&$items, $name, $extItems) {
            if (!$item instanceof File) {
                return;
            }
            if (empty($item->getExtension()) || !in_array(strtolower($item->getExtension()), $extItems)) {
                return;
            }
            if ($name === $item->getNameWithoutExtension()) {
                $items[] = $item;
            }
        });
        return $items;
    }
}