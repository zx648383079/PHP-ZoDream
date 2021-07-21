<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\App\Ipa;
use Module\Disk\Domain\FFmpeg;
use Module\Disk\Domain\Model\FileModel;
use Module\Disk\Domain\Repositories\DiskRepository;
use Module\Disk\Domain\WebVTT;
use Zodream\Image\Base\Box;
use Zodream\Image\Base\Font;
use Zodream\Image\Base\Point;
use Zodream\Image\Image;
use Zodream\Image\QrCode;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Infrastructure\Contracts\HttpContext;

class FileController extends Controller {

    public function rules() {
        return [
            'index' => '@',
            '*' => '*',
        ];
    }

    public function indexAction($id) {
        try {
            $disk = DiskRepository::driver()->file($id);
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('./', $ex->getMessage());
        }
        $name = 'index';
        switch ($disk['type']) {
            case FileModel::TYPE_APP:
                $name = 'app';
                break;
            case FileModel::TYPE_VIDEO:
                $name = 'video';
                break;
            default:
                break;
        }
        return $this->show($name, compact('disk'));
    }

    public function plistAction($id, Output $response) {
        try {
            $this->enableThrow();
            $data = DiskRepository::driver()->file($id);
            if ($data['extension'] !== 'ipa') {
                throw new \Exception('安装包错误');
            }
        } catch (\Exception $ex) {
            $response->header->setContentDisposition('error.plist');
            return $response->custom($ex->getMessage(), 'plist');
        }
        $data['path'];
        // TODO 解析安装包
        $response->header->setContentDisposition($data['name'].'.plist');
        return $response->custom(Ipa::getPlist($data['name'], '',
            url('./download', ['id' => $id]), '', '', ''), 'plist');
    }

    public function qrAction($id, Output $response) {
        try {
            $this->enableThrow();
            $data = DiskRepository::driver()->file($id);
        } catch (\Exception $ex) {
            $image = new QrCode();
            $image->encode($ex->getMessage());
            return $response->image($image);
        }
        $url = url('./download', ['id' => $id]);
        if ($data['extension'] == 'ipa') {
            $url = 'itms-services://?action=download-manifest&url='
                .urlencode(url('./file/plist', ['id' => $id]));
        }
        $image = new QrCode();
        $image->encode($url);
        return $response->image($image);
    }

    public function m3u8Action($id, Output $response) {
        $response->allowCors();
        try {
            $this->enableThrow();
            $data = DiskRepository::driver()->file($id);
            if ($data['type'] !== FileModel::TYPE_VIDEO) {
                throw new \Exception('文件不是视频');
            }
        } catch (\Exception $ex) {
            $response->header->setContentDisposition('error.m3u8');
            return $response->custom($ex->getMessage(), 'm3u8');
        }
        $video = $data['path'];
        $fileId = md5($data['id']);
        $baseFolder = DiskRepository::driver()->cacheFolder()->directory($fileId);
        $baseFolder->create();
        $m3u8File = $baseFolder->file($fileId.'.m3u8');
        if (!$m3u8File->exist()) {
            if ($data['extension'] != 'mp4') {
                $tmp = $baseFolder->file($fileId.'.mp4');
                if (!$tmp->exist()) {
                    FFmpeg::factory(null, $video)
                        ->overwrite()
                        ->set('c:v', 'libx264')
                        ->set('strict')
                        ->set('2')->output($tmp)->ready()->start()->join()->stop();
                }
                $video = $tmp;
            }
            $tsFile = $baseFolder->file($fileId.'.ts');
            if (!$tsFile->exist()) {
                FFmpeg::factory(null, $video)
                    ->overwrite()
                    ->set('vcodec', 'copy')
                    ->set('acodec', 'copy')
                    ->set('vbsf', 'h264_mp4toannexb')
                    ->output($tsFile)->ready()->start()->join()->stop();
            }
            FFmpeg::factory(null, $tsFile)
                ->overwrite()
                ->set('c', 'copy')
                ->set('map', 0)
                ->set('f', 'segment')
                ->set('segment_list', $m3u8File)
                ->set('segment_time', 5)
                ->output($baseFolder->file($fileId.'-%03d.ts'))->ready()->start()->join()->stop();
        }
        return $response->file($m3u8File);
    }

    public function invokeMethod(HttpContext $context, string $action, array $vars = []) {
        if (strpos($action, '.ts')) {
            return $this->tsAction($action, $context['response']);
        }
        return $context['route']->tryInvokeAction($this, $action, $vars, $context);
    }

    public function tsAction($name, Output $response) {
        $response->allowCors();
        $length = strlen($name);
        $id = substr($name, 0, $length - 6);
        $name = substr($name, $length - 6);
        $file = DiskRepository::driver()->cacheFolder()
            ->file(sprintf('%s/%s-%s', $id, $id, $name));
        return $response->file($file);
    }

    /**
     * 输出字幕
     * @param $id
     * @param Output $response
     * @return mixed
     */
    public function subtitlesAction($id, Output $response) {
        $response->allowCors();
        try {
            $this->enableThrow();
            $data = DiskRepository::driver()->file($id);
        } catch (\Exception $ex) {
            $vtt = new WebVTT('error', [0, 60, $ex->getMessage()]);
            return $response->export($vtt);
        }
        $data['path']->setExtension($data['extension'])
            ->setName($data['name']);
        $vtt = new WebVTT($data['name'], WebVTT::parseCuesFromFile($data['path'], $data['extension']));
        return $response->export($vtt);
    }

    public function musicAction($id, Output $response) {
        try {
            $this->enableThrow();
            $data = DiskRepository::driver()->file($id);
            if ($data['type'] !== FileModel::TYPE_MUSIC) {
                throw new \Exception('不是音乐');
            }
        } catch (\Exception $ex) {
            $response->header->setContentDisposition('error.mp3');
            return $response->custom($ex->getMessage(), 'mp3');
        }
        $data['path']->setExtension($data['extension'])
            ->setName($data['name']);
        return $response->file($data['path']);
    }

    public function videoAction($id, Output $response) {
        try {
            $this->enableThrow();
            $data = DiskRepository::driver()->file($id);
            if ($data['type'] !== FileModel::TYPE_VIDEO) {
                throw new \Exception('不是视频');
            }
        } catch (\Exception $ex) {
            $response->header->setContentDisposition('error.mp4');
            return $response->custom($ex->getMessage(), 'mp4');
        }
        $data['path']->setExtension($data['extension'])
            ->setName($data['name']);
        return $response->file($data['path'], 0);
    }

    public function imageAction($id, Output $response) {
        try {
            $this->enableThrow();
            $data = DiskRepository::driver()->file($id);
        } catch (\Exception $ex) {
            $image = new Image();
            $image->instance()->create(new Box(200, 100), '#fff');
            $image->instance()->text($ex->getMessage(),
                new Font((string)app_path()->file(config('disk.font')), 30, '#333'),
                new Point(30, 50));
            return $response->image($image);
        }
        $response->header->setContentType($data['extension'])
            ->setContentDisposition($data['name']);
        return $response->setParameter($data['path']);
    }

    public function thumbAction($id, Output $response) {
        try {
            $this->enableThrow();
            $data = DiskRepository::driver()->file($id);
            if ($data['type'] !== FileModel::TYPE_VIDEO) {
                throw new \Exception('无法预览');
            }
        } catch (\Exception $ex) {
            $image = new Image();
            $image->instance()->create(new Box(200, 100), '#fff');
            $image->instance()->text($ex->getMessage(),
                new Font((string)app_path()->file(config('disk.font')), 30, '#333'),
                new Point(30, 50));
            return $response->image($image);
        }
        $video = $data['path'];
        $fileId = md5($data['id']);
        $thumbFile = DiskRepository::driver()
            ->cacheFolder()
            ->file($fileId.'_thumb.jpg');
        if (!$thumbFile->exist()) {
            FFmpeg::factory(null, $video)
                ->overwrite()
                ->thumb('', 4)
                ->output($thumbFile)
                ->ready()->start()->join()->stop();
        }
        $response->header->setContentType('jpeg')
            ->setContentDisposition($data['name'].'_thumb.jpg');
        return $response->setParameter($thumbFile);
    }

    private function enableThrow() {
        if (!auth()->guest()) {
            return;
        }
        $token = request()->get('token');
        if (!empty($token) && cache()->store('disk')->has($token)) {
            return;
        }
        throw new \Exception('请先登录');
    }
}