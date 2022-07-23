<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Adapters;

use Exception;
use Module\Disk\Domain\Model\FileModel;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\FileObject;
use Zodream\Domain\Upload\BaseUpload;
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
            $items[] = $this->formatPageFile($root, $item);
        }
        return $page->setPage($items);
    }

    private function formatPageFile(Directory $root, FileObject $item): array {
        $fileId = $item->getRelative($root);
        return [
            'id' => $fileId,
            'name' => $item->getName(),
            'file_id' => $item instanceof Directory ? 0 : 1,
            'created_at' => Time::format($item instanceof Directory ? 0 : $item->createTime()),
            'updated_at' => Time::format($item instanceof Directory ? 0 : $item->modifyTime()),
            'file' => $item instanceof Directory ? null : $this->formatFile($item, $fileId)
        ];
    }

    private function formatFile(File $item, $fileId) {
        $data = [
            'size' => $item->size(),
            'extension' => strtolower($item->getExtension()),
        ];
        $data['type'] = FileModel::getType($data['extension']);
        switch ($data['type']) {
            case FileModel::TYPE_IMAGE:
                $data['thumb'] = $data['url'] = url('./file/image', ['id' => $fileId]);
                break;
            case FileModel::TYPE_VIDEO:
                $data['thumb'] = url('./file/thumb', ['id' => $fileId]);
                // $data['url'] = url('./file/m3u8', ['id' => $fileId]);
                $data['url'] = url('./file/video', ['id' => $fileId]);
                break;
            case FileModel::TYPE_MUSIC:
                $data['thumb'] = url('./file/thumb', ['id' => $fileId]);
                $data['url'] = url('./file/music', ['id' => $fileId]);
                break;
        }
        return $data;
    }

    public function remove($id) {
        $root = $this->root();
        $file = $root->file($id);
        if (!$file->exist()) {
            throw new \Exception('文件不存在');
        }
        $file->delete();
    }

    protected function getRealFolder($path) {
        if (!empty($path) && is_dir($path)) {
            return new Directory($path);
        }
        return parent::getRealFolder($path);
    }

    public function create(string $name, $parentId = '')
    {
        $root = $this->root();
        $folder = empty($parentId) ? $root : $root->directory($parentId);
        $item = $folder->addDirectory($name);
        return $this->formatPageFile($root, $item);
    }

    public function upload(BaseUpload $file, string $md5) {
        set_time_limit(0);
        $result = $file->setName($md5)
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
        return [
            'name' => $md5,
            'size' => $file->size(),
            'type' => ''
        ];
    }

    public function uploadFinish(string $md5, string $name, $parentId = '') {
        $file = $this->cacheFolder()->file($md5);
        if (!$file->exist() || $file->md5() !== $md5) {
            throw new Exception('uploaded file error');
        }
        $root = $this->root();
        $folder = empty($parentId) ? $root : $root->directory($parentId);
        $distFile = $folder->file($name);
        $i = 0;
        $dotIndex = strpos($name, '.');
        while ($distFile->exist()) {
            $i ++;
            if ($dotIndex < 0) {
                $tempName = sprintf('%s(%d)', $name, $i);
            } elseif ($dotIndex === 0) {
                $tempName = sprintf('(%d)%s', $i, $name);
            } else {
                $tempName = sprintf('%s(%d)%s', substr($name, 0, $dotIndex), $i,
                    substr($name, $dotIndex));
            }
            $distFile = $folder->file($tempName);
        }
        $file->move($distFile);
        return $this->formatPageFile($root, $distFile);
    }



    public function uploadCheck(string $md5, string $name, $parentId = ''): array {
        return [
            'code' => 2,
            'message' => 'MD5 Error'
        ];
    }

    public function rename($id, string $name)
    {
        $root = $this->root();
        $file = $root->file($id);
        if (!$file->exist()) {
            throw new \Exception('文件不存在');
        }
        $dist = $file->getDirectory()->file($name);
        if ($dist->exist()) {
            throw new \Exception('文件名重复');
        }
        if (!$file->rename($dist)) {
            throw new \Exception('重命名失败');
        }
        return $this->formatPageFile($root, $dist);
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
        $root = $this->root();
        $file = $root->file($id);
        if (!$file->exist()) {
            throw new \Exception('文件不存在');
        }
        $data = array_merge($this->formatFile($file, $id), [
            'id' => $id,
            'name' => $file->getName(),
            'path' => $file,
        ]);
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
        } elseif ($data['type'] === FileModel::TYPE_MUSIC) {
            $lyrics = $this->getLyrics($file);
            if (!empty($lyrics)) {
                $data['lyrics'] = array_map(function (File $item) use ($root) {
                    return [
                        'id' => $item->getRelative($root),
                    ];
                }, $lyrics);
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
        return $this->getLinkByExtension($file, config('disk.subtitles'));
    }

    protected function getLyrics(File $file) {
        return $this->getLinkByExtension($file, config('disk.lyrics'));
    }

    /**
     * 根据拓展名获取关联文件
     * @param File $file
     * @param array|string $extItems
     * @return array
     */
    protected function getLinkByExtension(File $file, array|string $extItems): array {
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