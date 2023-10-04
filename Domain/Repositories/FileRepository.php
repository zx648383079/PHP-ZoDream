<?php
declare(strict_types=1);
namespace Domain\Repositories;

use Domain\Providers\StorageProvider;
use Exception;
use Infrastructure\Environment;
use Module\Disk\Domain\FFmpeg;
use Zodream\Disk\File;
use Zodream\Disk\FileSystem;
use Zodream\Disk\StreamFinder;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Domain\Upload\UploadBase64;
use Zodream\Domain\Upload\UploadFile;
use Zodream\Domain\Upload\UploadRemote;
use Zodream\Html\Page;
use Zodream\Image\Base\Box;
use Zodream\Image\Base\Font;
use Zodream\Image\Base\Point;
use Zodream\Image\Image;
use Zodream\Image\WaterMark;
use Zodream\Infrastructure\Contracts\Http\Output;

class FileRepository {

    const DEFAULT_IMAGE = '/assets/images/thumb.jpg';
    private static array $configs = [];

    public static function storage() {
        return StorageProvider::publicStore();
    }

    /**
     * 获取字体文件
     * @return string
     * @throws Exception
     */
    public static function fontFile(): string {
        $fileName = config('disk.font');
        if (empty($fileName)) {
            return '';
        }
        $path = (string)app_path()->file($fileName);
        return is_file($path) ? $path : '';
    }

    /**
     * 获取图片网址，为空时获取默认图片
     * @param mixed|null $url
     * @return string
     */
    public static function formatImage(mixed $url = null): string {
        return url()->asset(empty($url) ? static::DEFAULT_IMAGE : $url);
    }

    public static function config(string $key = '', $default = null): mixed {
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
    public static function getList(array $allow = ['.*'], string $path = 'assets'): Page {
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

    public static function imageList(): Page {
        return static::getList(
            static::config('imageManagerAllowFiles'),
            static::config('imageManagerListPath'));
    }

    public static function fileList(): Page {
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
    public static function upload(string $fieldName, array $config, string $base64 = 'upload'): array {
        $request = request();
        return static::uploadFromData($base64 === 'base64' || $base64 === 'remote' ?
            $request->get($fieldName) :
            $request->file($fieldName), $config, $base64);
    }

    /**
     * 上传文件
     * @param string|array $data
     * @param array{maxSize: int, pathFormat: string, allowFiles: string[], oriName: string} $config
     * @param string $base64
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}
     * @throws Exception
     */
    public static function uploadFromData(array|string|null $data, array $config,
                                          string $base64 = 'upload'): array {
        if (is_null($data)) {
            throw new Exception('file error');
        }
        if ($base64 === 'base64') {
            $upload = new UploadBase64($data);
        } elseif ($base64 === 'remote') {
            $upload = new UploadRemote($data);
        } else {
            $upload = new UploadFile($data);
        }
        $config['allowFiles'] = static::formatExtension($config['allowFiles']);
        return static::uploading($upload, $config);
    }

    /**
     * @param BaseUpload $upload
     * @param array{maxSize: int, pathFormat: string, allowFiles: string[], oriName: string} $config
     * @param bool $isImage
     * @return array
     * @throws Exception
     */
    protected static function uploading(BaseUpload $upload, array $config, bool $isImage = false): array {
        if (!$upload->checkSize($config['maxSize'])) {
            throw new Exception((string)$upload->getError());
        }
        if ($upload instanceof UploadRemote && !$upload->checkType($config['allowFiles'])) {
            throw new Exception((string)$upload->getError());
        }
        if (isset($config['oriName'])) {
            $upload->setType(FileSystem::getExtension($config['oriName']));
        }
        if ($isImage && !$upload->validateDimensions()) {
            throw new Exception('图片尺寸有误');
        }
        $fileName = $upload->getRandomName($config['pathFormat']);
        $upload->setFile(public_path($fileName));
        $provider = static::storage();
        $res = $provider->addFile($upload);
        $url = $provider->toPublicUrl($res['url']);
        $thumb = static::loadThumb($res, $url, $upload);
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
     * 判断文件是否是危险文件
     * @param mixed $file
     * @return bool
     */
    public static function isDangerFile(mixed $file): bool {
        $finder = new StreamFinder([
            ['<%', '%>'],
            '<?php',
            ['<?=', '?>']
        ]);
        return $finder->matchFile($file instanceof BaseUpload ? $file->getFile() : $file);
    }

    protected static function loadThumb(array $res, string $file, BaseUpload $upload): string {
        $thumb = static::formatImage();
        if (in_array($res['extension'], static::config('imageAllowFiles'))) {
            $thumb = $file;
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
        return $thumb;
    }

    /**
     * 上传文件
     * @param string $fieldName
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}
     * @throws Exception
     */
    public static function uploadFile(string $fieldName = 'file'): array {
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
    public static function uploadBase64(string $fieldName = 'file'): array {
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
    public static function uploadVideo(string $fieldName = 'file'): array {
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
    public static function uploadAudio(string $fieldName = 'file'): array {
        return static::uploadVideo($fieldName);
    }

    /**
     * @param string $fieldName
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}
     * @throws Exception
     */
    public static function uploadImage(string $fieldName = 'file'): array {
        $items = static::uploadImages($fieldName, 1);
        if (empty($items)) {
            throw new Exception('图片上传失败');
        }
        return current($items);
    }

    /**
     * 批量上传多媒体文件，图片/视频
     * @param string $fieldName
     * @param int $count
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}[]
     * @throws Exception
     */
    public static function uploadFiles(string $fieldName = 'file', int $count = 0): array {
        $upload = new Upload();
        if (!$upload->upload($fieldName)) {
            throw new Exception('请选择上传文件');
        }
        $configs = [
            [
                'allowFiles' => static::formatExtension(static::config('imageAllowFiles')),
                'maxSize' => static::config('imageMaxSize'),
                'pathFormat' => static::config('imagePathFormat'),
            ],
            [
                'allowFiles' => static::formatExtension(static::config('videoAllowFiles')),
                'maxSize' => static::config('videoMaxSize'),
                'pathFormat' => static::config('videoPathFormat')
            ],
            [
                'pathFormat' => static::config('filePathFormat'),
                'maxSize' => static::config('fileMaxSize'),
                'allowFiles' => static::formatExtension(static::config('fileAllowFiles'))
            ]
        ];
        $files = [];
        $upload->each(function (BaseUpload $file) use ($configs, &$files) {
            foreach ($configs as $i => $config) {
                if (!$file->checkType($config['allowFiles'])) {
                    continue;
                }
                try {
                    $files[] = static::uploading($file, $config, $i < 1);
                } catch (Exception $ex) {
                    $file->setError($ex->getMessage());
                }
                return;
            }
            $file->setError('不允许上传此类型文件');
        }, $count);
        if (empty($files)) {
            throw new Exception(implode(',', $upload->getError()));
        }
        return $files;
    }

    /**
     * 批量上传图片
     * @param string $fieldName
     * @param int $count
     * @return array{url: string, title: string, original: string, type: string, size: int, thumb: string}[]
     * @throws Exception
     */
    public static function uploadImages(string $fieldName = 'file', int $count = 0): array {
        $upload = new Upload();
        if (!$upload->upload($fieldName)) {
            throw new Exception('请选择上传文件');
        }
        $files = [];
        $config = [
            'allowFiles' => static::formatExtension(static::config('imageAllowFiles')),
            'maxSize' => static::config('imageMaxSize'),
            'pathFormat' => static::config('imagePathFormat'),
        ];
        $upload->each(function (BaseUpload $file) use ($config, &$files) {
            try {
                $files[] = static::uploading($file, $config, true);
            } catch (Exception $ex) {
                $file->setError($ex->getMessage());
            }
        }, $count);
        if (empty($files)) {
            throw new Exception(implode(',', $upload->getError()));
        }
        return $files;
    }

    /**
     * 添加水印文字
     * @param File $file
     * @param string $text
     * @param int $position
     * @return void
     * @throws Exception
     */
    public static function addWater(File $file, string $text, int $position): void {
        $image = new WaterMark();
        $image->instance()->loadResource($file);
        $font = new Font(static::fontFile(), 12, '#fff');
        $textBox = $image->instance()->fontSize($text, $font);
        list($x, $y) = $image->getPointByDirection(match ($position) {
            3 => WaterMark::RightBottom,
            2 => WaterMark::LeftBottom,
            1 => WaterMark::RightTop,
            default => WaterMark::LeftTop,
        }, $textBox->getWidth(), $textBox->getHeight(), 20);
        $image->addText($text, $x + 2, $y + 2, $font->getSize(), '#777', $font->getFile());
        $image->addText($text, $x, $y, $font->getSize(), $font->getColor(), $font->getFile());
        $image->save();
    }

    /**
     * 画文字
     * @param string $text
     * @return Output
     */
    public static function paintText(string $text): Output {
        $image = new Image();
        $image->instance()->create(new Box(200, 100), '#fff');
        $image->instance()->text($text,
            new Font(static::fontFile(), 30, '#333'),
            new Point(30, 50));
        return response()->image($image);
    }

    protected static function formatExtension(array $data): array {
        return array_map(function (string $item) {
            return $item[0] === '.' ? substr($item, 1) : $item;
        }, $data);
    }
}