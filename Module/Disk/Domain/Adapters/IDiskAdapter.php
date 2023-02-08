<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Adapters;

use Zodream\Domain\Upload\BaseUpload;
use Zodream\Html\Page;

interface IDiskAdapter {

    public function catalog($id, $path): Page;

    public function search(string $keywords, string $type): Page;

    public function remove($id);

    public function create(string $name, $parentId = '');

    /**
     * 上传单个文件
     * @param array $fileData
     * @param string $md5
     * @param string $name
     * @param string|int $parentId
     * @return mixed
     */
    public function uploadFile(array $fileData, string $md5, string $name, string|int $parentId = '');

    /**
     * 上传分块
     * @param array $fileData
     * @param string $cacheName
     * @return mixed
     */
    public function uploadChunk(array $fileData, string $cacheName);

    /**
     * 合并分块
     * @param string $md5
     * @param string $name
     * @param array $chunkNames
     * @param string|int $parentId
     * @return mixed
     */
    public function uploadFinish(string $md5, string $name, array $chunkNames, string|int $parentId = '');

    /**
     * 验证MD5
     * @param string $md5
     * @param string $name
     * @param string|int $parentId
     * @return array
     */
    public function uploadCheck(string $md5, string $name, string|int $parentId = ''): array;

    public function rename($id, string $name);

    public function copy(array $data);

    public function move(array $data);

    public function file($id): array;
}