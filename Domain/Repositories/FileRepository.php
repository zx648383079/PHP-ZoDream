<?php
declare(strict_types=1);
namespace Domain\Repositories;

use Domain\Providers\StorageProvider;
use Exception;
use Infrastructure\Environment;
use Module\Disk\Domain\FFmpeg;
use Zodream\Disk\FileSystem;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Domain\Upload\UploadBase64;
use Zodream\Domain\Upload\UploadFile;
use Zodream\Domain\Upload\UploadRemote;
use Zodream\Html\Page;

class FileRepository {

    private static array $configs = [];

    public static function storage() {
        return StorageProvider::publicStore();
    }

    public static function config(string $key = '', $default = null) {
        if (empty(static::$configs)) {
            static::$configs = config('ueditor');
        }
        if (empty($key)) {
            return static::$configs;
        }
        return isset(static::$configs[$key]) || array_key_exists($key, static::$configs) ?
            static::$configs[$key] : $default;
    }

    /**
     * @param array|string[] $allow
     * @param string $path
     * @return Page<int, array{url: string, thumb: string, mtime: int}>
     * @throws Exception
     */
    public static function getList(array $allow = ['.*'], string $path = 'assets') {
        $allow = substr(str_replace('.', '|', implode('', $allow)), 1);
        $path = public_path($path)->getFullName();
        $files = Environment::getfiles($path, $allow);
        $page = new Page($files);
        $page->map(function ($item) {
            $item['url'] = url()->asset($item['url']);
            $item['thumb'] = $item['url'];
            return $item;
        });
        return $page;
    }

    public static function imageList() {
        return static::getList(
            static::config('imageManagerAllowFiles'),
            static::config('imageManagerListPath'));
    }

    public static function fileList() {
        return static::getList(
            static::config('fileManagerAllowFiles'),
            static::config('fileManagerListPath'));
    }

    /**
     * 上传文件
     * @param string $fieldName
     * @param array $config
     * @param string $base64
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}
     * @throws Exception
     */
    public static function upload(string $fieldName, array $config, string $base64 = 'upload') {
        return static::uploadFromData($base64 === 'base64' || $base64 === 'remote' ? request()->get($fieldName) :
            request()->file($fieldName), $config, $base64);
    }

    /**
     * 上传文件
     * @param string|array $data
     * @param array $config
     * @param string $base64
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}
     * @throws Exception
     */
    public static function uploadFromData(array|string $data, array $config, string $base64 = 'upload') {
        if ($base64 === 'base64') {
            $upload = new UploadBase64($data);
        } elseif ($base64 === 'remote') {
            $upload = new UploadRemote($data);
        } else {
            $upload = new UploadFile($data);
        }
        if (!$upload->checkSize($config['maxSize'])) {
            throw new Exception($upload->getError());
        }
        if ($upload instanceof UploadRemote && !$upload->checkType(static::formatExtension($config['allowFiles']))) {
            throw new Exception($upload->getError());
        }
        if (isset($config['oriName'])) {
            $upload->setType(FileSystem::getExtension($config['oriName']));
        }
        $fileName = $upload->getRandomName($config['pathFormat']);
        $upload->setFile(public_path($fileName));
        $res = static::storage()->addFile($upload);
        $url = url()->asset($fileName);
        $thumb = url()->asset('assets/images/thumb.jpg');
        if (in_array($res['extension'], static::config('imageAllowFiles'))) {
            $thumb = $url;
        } elseif (in_array($res['extension'], static::config('videoAllowFiles'))) {
            try {
                $videoFile = $upload->getFile();
                $thumbFile = $videoFile->getDirectory()
                    ->file($videoFile->getNameWithoutExtension().'_thumb.jpg');
                if (!$thumbFile->exist()) {
                    FFmpeg::factory(config('disk.ffmpeg'), $videoFile)
                        ->overwrite()
                        ->thumb('', 4)
                        ->output($thumbFile)
                        ->ready()->start()->join()->stop();
                }
                if ($thumbFile->exist()) {
                    $thumb = url()->asset($thumbFile->getRelative(public_path()));
                }
            } catch (Exception) {}
        }
        return [
            'url' => $url,
            'title' => $res['title'],
            'original' => $res['title'],
            'type' => $res['extension'],
            'size' => $res['size'],
            'thumb' => $thumb,
        ];
    }

    /**
     * 上传文件
     * @param string $fieldName
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}
     * @throws Exception
     */
    public static function uploadFile(string $fieldName = 'file') {
        return static::upload(
            $fieldName, array(
                'pathFormat' => static::config('filePathFormat'),
                'maxSize' => static::config('fileMaxSize'),
                'allowFiles' => static::config('fileAllowFiles')
            )
        );
    }

    /**
     * 上传base64图片
     * @param string $fieldName
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}
     * @throws Exception
     */
    public static function uploadBase64(string $fieldName = 'file') {
        return static::upload(
            $fieldName, array(
            'pathFormat' => static::config('scrawlPathFormat'),
            'maxSize' => static::config('scrawlMaxSize'),
            'allowFiles' => static::config('scrawlAllowFiles'),
            'oriName' => 'scrawl.png'
        ), 'base64');
    }

    /**
     * 上传视频
     * @param string $fieldName
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}
     * @throws Exception
     */
    public static function uploadVideo(string $fieldName = 'file') {
        return static::upload(
            $fieldName, array(
            'pathFormat' => static::config('videoPathFormat'),
            'maxSize' => static::config('videoMaxSize'),
            'allowFiles' => static::config('videoAllowFiles')
        ));
    }

    /**
     * 上传语音
     * @param string $fieldName
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}
     * @throws Exception
     */
    public static function uploadAudio(string $fieldName = 'file') {
        return static::uploadVideo($fieldName);
    }

    /**
     * @param string $fieldName
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}
     * @throws Exception
     */
    public static function uploadImage(string $fieldName = 'file') {
        $items = static::uploadImages($fieldName, 1);
        if (empty($items)) {
            throw new Exception('图片上传失败');
        }
        return current($items);
    }

    /**
     * @param string $fieldName
     * @param int $count
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}[]
     * @throws Exception
     */
    public static function uploadImages(string $fieldName = 'file', int $count = 0) {
        $upload = new Upload();
        if (!$upload->upload($fieldName)) {
            throw new Exception('请选择上传文件');
        }
        $allowType = static::formatExtension(static::config('imageAllowFiles'));
        $storage = static::storage();
        $files = [];
        $upload->each(function (BaseUpload $file) use ($allowType, $storage, &$files) {
            if (!$file->checkSize(static::config('imageMaxSize'))) {
                $file->setError('超出最大尺寸限制');
                return;
            }
            if (!$file->checkType($allowType)) {
                $file->setError('不允许上传此类型文件');
                return;
            }
            if (!$file->validateDimensions()) {
                $file->setError('图片尺寸有误');
                return;
            }
            $fileName = $file->getRandomName(static::config('imagePathFormat'));
            $file->setFile(public_path($fileName));
            try {
                $res = $storage->addFile($file);
            } catch (Exception $ex) {
                $file->setError($ex->getMessage());
                return;
            }
            $url = url()->asset($fileName);
            $files[] = [
                'url' => $url,
                'title' => $file->getFile()->getName(),
                'original' => $res['title'],
                'type' => $res['extension'],
                'size' => $res['size'],
                'thumb' => $url
            ];
        }, $count);
        if (empty($files)) {
            throw new Exception(implode(',', $upload->getError()));
        }
        return $files;
    }

    protected static function formatExtension(array $data): array {
        return array_map(function (string $item) {
            return $item[0] === '.' ? substr($item, 1) : $item;
        }, $data);
    }
}