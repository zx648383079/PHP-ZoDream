<?php
declare(strict_types=1);
namespace Domain\Providers;

use Zodream\Database\DB;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Query\Builder;
use Zodream\Database\Schema\Table;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\UploadFile;
use Zodream\Infrastructure\Contracts\Http\Output;

class StorageProvider {

    const FILE_TABLE = 'base_file';
    const FILE_LOG_TABLE = 'base_file_log';
    const FILE_QUOTE_TABLE = 'base_file_quote';

    public static function store(Directory $root, int $tag = 3): StorageProvider {
        return new static($root, $tag);
    }

    public static function publicStore(): StorageProvider {
        return static::store(public_path()->childDirectory(config('view.asset_directory')), 1);
    }

    public static function privateStore(): StorageProvider {
        return static::store(app_path()->childDirectory('data/storage'), 2);
    }

    public function __construct(
        protected Directory $root,
        protected int $tag = 3
    ) {
        $this->root->create();
    }

    protected function query(): Builder {
        return DB::table(static::FILE_TABLE);
    }

    protected function logQuery(): Builder {
        return DB::table(static::FILE_LOG_TABLE);
    }

    protected function quoteQuery(): Builder {
        return DB::table(static::FILE_QUOTE_TABLE);
    }

    public function migration(Migration $migration): Migration {
        return $migration->append(static::FILE_TABLE, function(Table $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('extension', 10);
            $table->char('md5', 32)->unique();
            $table->string('path', 200);
            $table->uint('folder', 2)->default(1);
            $table->string('size')->default('0');
            $table->timestamps();
        })->append(static::FILE_LOG_TABLE, function(Table $table) {
            $table->id();
            $table->uint('file_id');
            $table->uint('item_type')->default(0);
            $table->uint('item_id');
            $table->string('data')->default('');
            $table->timestamp('created_at');
        })->append(static::FILE_QUOTE_TABLE, function(Table $table) {
            $table->id();
            $table->uint('file_id');
            $table->uint('item_type')->default(0);
            $table->uint('item_id');
            $table->uint('user_id')->default(0);
            $table->timestamp('created_at');
        });
    }

    /**
     * @param string $md5
     * @return array{url: string, title: string, extension: string, size: int}
     * @throws \Exception
     */
    public function addMd5(string $md5): array {
        if (empty($md5)) {
            throw new \Exception('not found');
        }
        $model = static::query()->where('md5', $md5)->first();
        if (empty($model)) {
            throw new \Exception('not found');
        }
        return [
            'url' => $model['path'],
            'title' => $model['name'],
            'extension' => '.'.$model['extension'],
            'size' => $model['size'],
        ];
    }

    /**
     *
     * @param BaseUpload|array $upload
     * @return array{url: string, title: string, extension: string, size: int}
     * @throws \Exception
     */
    public function addFile(BaseUpload|array $upload): array {
        if (is_array($upload)) {
            $upload = new UploadFile($upload);
        }
        $file = $upload->getFile();
        if (empty($file)) {
            $file = $this->root->file($upload->getRandomName());
            $upload->setFile($file);
        }
        $path = $file->getRelative($this->root);
        if (empty($path) || !$upload->save()) {
            // 保存文件位置可能不在目录下
            throw new \Exception('add file error');
        }
        $md5 = $file->md5();
        try {
            $model = $this->addMd5($md5);
            $file->delete();
            return $model;
        } catch (\Exception $ex) {
        }
        $model = [
            'name' => $upload->getName(),
            'extension' => $upload->getType(),
            'path' => $path,
            'size' => $file->size(),
            'md5' => $md5,
            'folder' => $this->tag,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        $model['id'] = $this->query()->insert($model);
        if (empty($model['id'])) {
            $file->delete();
            throw new \Exception('add file error');
        }
        return [
            'url' => $model['path'],
            'title' => $model['name'],
            'extension' => !empty($model['extension']) ? '.'.$model['extension'] : '',
            'size' => $model['size'],
        ];
    }

    public function get(string $url): array {
        $model = $this->query()->where('folder', $this->tag)->where('path', $url)->first();
        if (empty($model)) {
            throw new \Exception('not found file');
        }
        return $model;
    }

    public function addQuote(string $url, int $itemType, int $itemId) {
        if (empty($url)) {
            return;
        }
        $id = $this->query()->where('folder', $this->tag)->where('path', $url)->value('id');
        if ($id < 1) {
            throw new \Exception('not found file');
        }
        $count = $this->quoteQuery()->where('file_id', $id)
            ->where('item_id', $itemId)->where('item_type', $itemType)->count();
        if ($count > 0) {
            return;
        }
        $this->quoteQuery()->insert([
            'file_id' => $id,
            'item_id' => $itemId,
            'item_type' => $itemType,
            'user_id' => auth()->id(),
            'created_at' => time(),
        ]);
    }

    public function removeQuote(int $itemType, int $itemId) {
        $this->quoteQuery()->where('item_type', $itemType)
            ->where('item_id', $itemId)->delete();
    }

    public function removeFile(string $url) {
        if (empty($url)) {
            return;
        }
        $id = $this->query()->where('folder', $this->tag)->where('path', $url)->value('id');
        if ($id < 1) {
            throw new \Exception('not found file');
        }
        $this->quoteQuery()->where('file_id', $id)->delete();
        $this->logQuery()->where('file_id', $id)->delete();
        $this->query()->where('id', $id)->delete();
    }

    /***
     * 返回本地文件 问
     * @param string $url
     * @param bool $useOriginalName 使用原文件名
     * @return File
     * @throws \Exception
     */
    public function getFile(string $url, bool $useOriginalName = true): File {
        $model = $this->get($url);
        $file = $this->root->file($model['path']);
        if (!$useOriginalName) {
            return $file;
        }
        return $file->setName($model['name']);
    }

    public function output(Output $output, string $url): Output {
        $output->file($this->getFile($url));
        return $output;
    }
}