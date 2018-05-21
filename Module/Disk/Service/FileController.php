<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\FFmpeg;
use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Model\FileModel;
use Zodream\Service\Factory;

class FileController extends Controller {

    public function indexAction($id) {
        $disk = DiskModel::auth()->where('id', $id)->first();
        return $this->show(compact('disk'));
    }

    public function m3u8Action($id) {
        $file = FileModel::find($id);
        if (empty($file) || $file->type != FileModel::TYPE_VIDEO) {
            return $this->jsonFailure('');
        }
        $tool = 'D:\ffmpeg\bin\ffmpeg.exe';
        $video = Factory::root()->file($this->configs['disk'].$file->location);
        if ($file->extension != 'mp4') {
            $tmp = Factory::public_path()->file($video->getName().'.mp4');
            FFmpeg::factory($tool, $video)
                ->overwrite()
                ->set('c:v', 'libx264')
                ->set('strict')
                ->set('2')->output($tmp)->ready()->start()->join()->stop();
            $video = $tmp;
        }
        $ts = '.ts';
        FFmpeg::factory($tool, $video)
            ->overwrite()
            ->set('vcodec', 'copy')
            ->set('acodec', 'copy')
            ->set('vbsf', 'h264_mp4toannexb')
            ->output($ts)->ready()->start()->join()->stop();
        FFmpeg::factory($tool, $ts)
            ->overwrite()
            ->set('c', 'copy')
            ->set('map', 0)
            ->set('f', 'segment')
            ->set('segment_list', '.m3u8')
            ->set('segment_time', 5)
            ->output('-%03d.ts')->ready()->start()->join()->stop();
    }

    public function tsAction() {

    }
}