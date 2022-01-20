<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\Repositories\MediaRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MediaController extends Controller {


    public function indexAction(string $keywords = '', string $type = '') {
        try {
            return $this->renderPage(MediaRepository::getList(
                $this->weChatId(),
                $keywords, $type));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                MediaRepository::getSelf($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'type' => 'required|string:0,10',
                'material_type' => 'int:0,9',
                'title' => 'string:0,200',
                'thumb' => 'string:0,200',
                'show_cover' => 'int:0,9',
                'open_comment' => 'int:0,9',
                'only_comment' => 'int:0,9',
                'content' => '',
                'parent_id' => 'int',
                'media_id' => 'string:0,100',
                'url' => 'string:0,255',
            ]);
            return $this->render(
                MediaRepository::save($this->weChatId(), $data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            MediaRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function asyncAction(int $id) {
        try {
            MediaRepository::async($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function pullAction(string $type = '') {
        try {
            MediaRepository::pull($this->weChatId(), $type);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function searchAction(string $keywords = '', string $type = '', int|array $id = 0) {
        try {
            return $this->renderPage(MediaRepository::search(
                $this->weChatId(),
                $keywords, $type, $id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}