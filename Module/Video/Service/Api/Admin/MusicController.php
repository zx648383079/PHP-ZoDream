<?php
declare(strict_types=1);
namespace Module\Video\Service\Api\Admin;

use Module\Video\Domain\Repositories\MusicRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MusicController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            MusicRepository::getList($keywords),
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                MusicRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,255',
                'singer' => 'string:0,20',
                'duration' => 'int:0,9999',
                'path' => 'string:0,255',
            ]);
            return $this->render(
                MusicRepository::save($data)
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
}