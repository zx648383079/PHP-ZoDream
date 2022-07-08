<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api\Admin;

use Module\OnlineTV\Domain\Repositories\MusicRepository;
use Module\OnlineTV\Domain\Repositories\TVRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MusicController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            MusicRepository::getList($keywords)
        );
    }


    public function detailAction(int $id) {
        try {
            return $this->render(MusicRepository::getEdit($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }

    public function saveAction(Input $input) {
        try {
            return $this->render(
                MusicRepository::save($input->validate([
                    'id' => 'int',
                    'name' => 'required|string:0,255',
                    'cover' => 'string:0,255',
                    'album' => 'string:0,20',
                    'artist' => 'string:0,20',
                    'duration' => 'int',
                    'status' => 'int:0,127',
                    'files' => ''
              ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            MusicRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function fileAction(int $music) {
        return $this->renderData(
            MusicRepository::fileList($music)
        );
    }

    public function fileSaveAction(Input $input) {
        try {
            return $this->render(
                MusicRepository::fileSave($input->validate([
                    'id' => 'int',
                    'music_id' => 'required|int',
                    'file_type' => 'int:0,127',
                    'file' => 'required|string:0,255',
                ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function fileDeleteAction(int $id) {
        try {
            MusicRepository::fileRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function uploadAction(Input $input) {
        try {
            return $this->render(TvRepository::storage()->addFile($input->file('file')));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}