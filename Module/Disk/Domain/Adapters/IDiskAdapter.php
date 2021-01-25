<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Adapters;

use Zodream\Html\Page;

interface IDiskAdapter {

    public function catalog($id, $path): Page;

    public function remove($id);

    public function create(array $data);

    public function upload($file);

    public function checkMd5(array $data);

    public function rename(array $data);

    public function copy(array $data);

    public function move(array $data);

    public function file($id): array;
}