<?php
declare(strict_types=1);
namespace Domain\Providers;

use Zodream\Database\DB;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Query\Builder;
use Zodream\Database\Schema\Table;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\FileObject;
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
        $model = $this->getByMd5($md5);
        return [
            'url' => $model['path'],
            'title' => $model['name'],
            'extension' => '.'.$model['extension'],
            'size' => $model['size'],
        ];
    }

    protected function getByMd5(string $md5): array {
        if (empty($md5)) {
            throw new \Exception('not found');
        }
        $model = static::query()->where('md5', $md5)->first();
        if (empty($model)) {
            throw new \Exception('not found');
        }
        return $model;
    }

    /**
     * 插入数据
     * @param BaseUpload|array $upload
     * @return array{url: string, title: string, extension: string, size: int}
     * @throws \Exception
     */
    public function addFile(BaseUpload|array $upload): array {
        $model = $this->insertFile($upload);
        return $this->formatLog($model);
    }

    /**
     * 插入数据
     * @param BaseUpload|array $upload
     * @param bool $backFile 是否需要返回文件路径
     * @return array{id: int,name: string,extension: string,path:string,size: int,md5: string,folder: string, file: File} 返回 log 数据
     * @throws \Exception
     */
    public function insertFile(BaseUpload|array $upload, bool $backFile = false): array {
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
        return $this->insertFileLog($file, [
            'name' => $upload->getName(),
            'type' => $upload->getType(),
        ], $backFile);
    }

    /**
     * 添加log 记录
     * @param File $file
     * @param array $rawData
     * @param bool $backFile
     * @return array{id: int,name: string,extension: string,path:string,size: int,md5: string,folder: string, file: File} 返回的log数据
     * @throws \Exception
     */
    protected function insertFileLog(File $file, array $rawData = [], bool $backFile = false): array {
        $path = $file->getRelative($this->root);
        if (empty($path)) {
            // 保存文件位置可能不在目录下
            throw new \Exception('found file error');
        }
        $md5 = $file->md5();
        try {
            $model = $this->getByMd5($md5);
            $distFile = $this->toFile($model['path']);
            if (!$distFile->exist()) {
                $file->move($distFile);
            } else {
                $file->delete();
            }
            if ($backFile) {
                $model['file'] = $distFile;
            }
            return $model;
        } catch (\Exception $ex) {
        }
        $model = [
            'name' => $rawData['name'] ?? $file->getName(),
            'extension' => $rawData['type'] ?? $file->getExtension(),
            'path' => ltrim($path, '/'),
            'size' => $file->size(),
            'md5' => $md5,
            'folder' => $this->tag,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        $model['id'] = $this->query()->insert($model);
        if (empty($model['id'])) {
            $file->delete();
            throw new \Exception('add file log error');
        }
        if ($backFile) {
            $model['file'] = $file;
        }
        return $model;
    }

    /**
     * 格式化成前台输出的数据
     * @param array{id: int,name: string,extension: string,path:string,size: int,md5: string,folder: string} $log
     * @return array{url: string, title: string, extension: string, size: int}
     */
    public function formatLog(array $log): array {
        return [
            'url' => $log['path'],
            'title' => $log['name'],
            'extension' => !empty($log['extension']) ? '.'.$log['extension'] : '',
            'size' => $log['size'],
        ];
    }

    /**
     * 复制文件到这里
     * @param File $sourceFile
     * @param array $rawData
     * @param bool $backFile
     * @return array{id: int,name: string,extension: string,path:string,size: int,md5: string,folder: string, file: File} 返回 log 数据
     * @throws \Exception
     */
    public function copyFile(File $sourceFile, array $rawData = [], bool $backFile = false): array {
        if ($sourceFile->isChild($this->root)) {
            $file = $sourceFile;
        } else {
            $file = $this->root->file(sprintf('%s_%s', time(), $sourceFile->getName()));
        }
        return $this->insertFileLog($file, $rawData, $backFile);
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
        $model = $this->get($url);
        $this->quoteQuery()->where('file_id', $model['id'])->delete();
        $this->logQuery()->where('file_id', $model['id'])->delete();
        $this->query()->where('id', $model['id'])->delete();
        $this->toFile($model)->delete();
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
        $file = $this->toFile($model['path']);
        if (!$useOriginalName) {
            return $file;
        }
        return $file->setName($model['name']);
    }

    /***
     * 确认一下文件
     * @return void
     */
    public function reload() {
        $this->root->mapDeep(function (FileObject $file) {
            if ($file instanceof Directory) {
                return;
            }
            $md5 = $file->md5();
            if (static::query()->where('md5', $md5)->count() > 0) {
                return;
            }
            $path = $file->getRelative($this->root);
            $model = static::query()->where('path', $path)->where('folder', $this->tag)->first();
            if (empty($model)) {
                $this->query()->insert([
                    'name' => $file->getName(),
                    'extension' => $file->getExtension(),
                    'path' => $path,
                    'size' => $file->size(),
                    'md5' => $md5,
                    'folder' => $this->tag,
                    'created_at' => time(),
                    'updated_at' => time(),
                ]);
                return;
            }
            if ($model['md5'] !== $md5) {
                static::query()->where('id', $model['id'])
                    ->update([
                        'md5' => $md5
                    ]);
            }

        });
    }

    protected function toFile(string|array $path): File {
        if (is_array($path)) {
            $path = $path['path'] ?? '';
        }
        if (empty($path)) {
            throw new \Exception('path is empty');
        }
        return $this->root->file($path);
    }

    /**
     * 输出文件
     * @param Output $output
     * @param string $url
     * @param bool $notSplit 是否不启用分块下载，对于流媒体播放不要启用
     * @return Output
     * @throws \Exception
     */
    public function output(Output $output, string $url, bool $notSplit = false): Output {
        $file = $this->getFile($url);
        if ($notSplit) {
            $output->file($file, 0);
        } else {
            $output->file($file);
        }
        return $output;
    }

    /**
     * 判断当前根目录是否是对外开放的
     * @return bool
     * @throws \Exception
     */
    public function isPublic(): bool {
        return str_starts_with((string)$this->root, (string)public_path());
    }

    /**
     * 从路径转成访问网址
     * @param string $path
     * @return string 不在公共目录返回 ''
     * @throws \Exception
     */
    public function toPublicUrl(string $path): string {
        $root = (string)public_path();
        $file = (string)$this->root->file($path);
        if (str_starts_with($file, $root)) {
            return url()->asset(substr($file, strlen($root)));
        }
        return '';
    }

    /**
     * 从网址转成路径
     * @param string $path
     * @return string
     * @throws \Exception
     */
    public function fromPublicUrl(string $path): string {
        $assetName = config('view.asset_directory', '');
        if (!empty($assetName)) {
            if (!str_ends_with($assetName, '/')) {
                $assetName .= '/';
            }
            $i = strpos($path, $assetName);
            if ($i !== false) {
                return substr($path, $i + strlen($assetName));
            }
        }
        if (str_contains($path, '://')) {
            $path = parse_url($path, PHP_URL_PATH);
        }
        $file = (string)public_path()->file($path);
        $root = (string)$this->root;
        if (str_starts_with($file, $root)) {
            return ltrim(substr($file, strlen($root)), '/');
        }
        return $path;
    }
}