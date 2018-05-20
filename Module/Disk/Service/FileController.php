<?php
namespace Module\Disk\Service;

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
            $cmd = sprintf('%s -i %s -y -c:v libx264 -strict -2 %s', $tool, $video->getFullName(), $tmp->getFullName());
            shell_exec($cmd);
            $video = $tmp;
        }
        $cmd = sprintf('%s -y -i %s -vcodec copy -acodec copy -vbsf h264_mp4toannexb %s.ts', $tool, $video->getFullName(), $video->getName());
        echo $cmd, '<br/>';
        //shell_exec($cmd);
        $cmd = sprintf('%s -i %s.ts -c copy -map 0 -f segment -segment_list %s.m3u8 -segment_time 5 %s', $tool, $video->getName(), $video->getName(), $video->getName()).'-%03d.ts';
        echo $cmd;
        //shell_exec($cmd);
    }

    public function tsAction() {

    }
}