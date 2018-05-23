<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\FFmpeg;
use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Model\FileModel;
use Zodream\Service\Factory;

class FileController extends Controller {

    public function indexAction($id) {
        $disk = DiskModel::auth()->where('id', $id)->one();
        return $this->show(compact('disk'));
    }

    public function m3u8Action($id) {
        $file = FileModel::find($id);
        if (empty($file) || $file->type != FileModel::TYPE_VIDEO) {
            return $this->jsonFailure('');
        }
        $tool = 'D:\ffmpeg\bin\ffmpeg.exe';
        $video = $this->diskFolder->file($file->location);
        $baseFolder = $this->diskFolder->directory($file->id);
        $baseFolder->create();
        $m3u8File = $baseFolder->file($file->id.'.m3u8');
        if (!$m3u8File->exist()) {
            if ($file->extension != 'mp4') {
                $tmp = $baseFolder->file($file->id.'.mp4');
                if (!$tmp->exist()) {
                    FFmpeg::factory($tool, $video)
                        ->overwrite()
                        ->set('c:v', 'libx264')
                        ->set('strict')
                        ->set('2')->output($tmp)->ready()->start()->join()->stop();
                }
                $video = $tmp;
            }
            $tsFile = $baseFolder->file($file->id.'.ts');
            if (!$tsFile->exist()) {
                FFmpeg::factory($tool, $video)
                    ->overwrite()
                    ->set('vcodec', 'copy')
                    ->set('acodec', 'copy')
                    ->set('vbsf', 'h264_mp4toannexb')
                    ->output($tsFile)->ready()->start()->join()->stop();
            }
            FFmpeg::factory($tool, $tsFile)
                ->overwrite()
                ->set('c', 'copy')
                ->set('map', 0)
                ->set('f', 'segment')
                ->set('segment_list', $m3u8File)
                ->set('segment_time', 5)
                ->output($baseFolder->file($file->id.'-%03d.ts'))->ready()->start()->join()->stop();
        }
        return Factory::response()->file($m3u8File);
    }

    public function runMethod($action, array $vars = array()) {
        if (strpos($action, '.ts')) {
            return $this->tsAction($action);
        }
        return parent::runMethod($action, $vars);
    }

    public function tsAction($name) {
        $length = strlen($name);
        $id = substr($name, 0, $length - 6);
        $name = substr($name, $length - 6);
        $file = $this->diskFolder->file(sprintf('%s/%s-%s', $id, $id, $name));
        return Factory::response()->file($file);
    }

    public function musicAction($id) {
        $model = DiskModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('ID ERROR!');
        }
        if ($model->file->type != FileModel::TYPE_MUSIC) {
            return $this->jsonFailure('TYPE ERROR');
        }
        $file = $this->diskFolder->file($model->file->location);
        if (!$file->exist()) {
            return $this->jsonFailure('FILE ERROR!');
        }
        $file->setExtension($model->file->extension)
            ->setName($model->name);
        return Factory::response()
            ->file($file);
    }
}