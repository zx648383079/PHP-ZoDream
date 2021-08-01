<?php
declare(strict_types=1);
namespace Domain\Repositories;

use Exception;
use Infrastructure\Environment;
use Infrastructure\Uploader;
use Module\Disk\Domain\FFmpeg;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Html\Page;

class FileRepository {

    private static array $configs = [];

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
     * 删除文件
     * @param string $fieldName
     * @param array $config
     * @param string $base64
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}
     * @throws Exception
     */
    public static function upload(string $fieldName, array $config, string $base64 = 'upload') {
        $upload = new Uploader($fieldName, $config, $base64);
        $res = $upload->getFileInfo();
        if ($res['state'] !== 'SUCCESS') {
            throw new Exception($res['state']);
        }
        $url = url()->asset($res['url']);
        $ext = strtolower(strrchr($res['original'], '.'));
        $thumb = url()->asset('assets/images/thumb.jpg');
        if (in_array($ext, static::config('imageAllowFiles'))) {
            $thumb = $ext;
        } elseif (in_array($ext, static::config('videoAllowFiles'))) {
            try {
                $videoFile = public_path($res['url']);
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
            'original' => $res['original'],
            'type' => $res['type'],
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
        $allowType = array_map(function ($item) {
            return substr($item, 1);
        }, static::config('imageAllowFiles'));
        $files = [];
        $upload->each(function (BaseUpload $file) use ($allowType, &$files) {
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
            $file->setFile(public_path($file->getRandomName(static::config('imagePathFormat'))));
            if (!$file->save()) {
                return;
            }
            $url = url()->asset($file->getFile()->getRelative(public_path()));
            $files[] = [
                'url' => $url,
                'title' => $file->getFile()->getName(),
                'original' => $file->getName(),
                'type' => '.'.$file->getType(),
                'size' => $file->getSize(),
                'thumb' => $url
            ];
        }, $count);
        if (empty($files)) {
            throw new Exception(implode(',', $upload->getError()));
        }
        return $files;
    }
}