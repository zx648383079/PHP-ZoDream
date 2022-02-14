<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Adapters;

use Zodream\Domain\Upload\BaseUpload;
use Zodream\Html\Page;

interface IDiskAdapter {

    public function catalog($id, $path): Page;

    public function remove($id);

    public function create(string $name, $parentId = '');

    public function upload(BaseUpload $file, string $md5);

    public function uploadChunk(array $fileData, string $md5);

    public function uploadFinish(string $md5, string $name, $parentId = '');

    public function uploadCheck(string $md5, string $name, $parentId = ''): array;

    public function rename($id, string $name);

    public function copy(array $data);

    public function move(array $data);

    public function file($id): array;
}