<?php
declare(strict_types=1);
namespace Module\SEO\Service\Api\Admin;

use Module\SEO\Domain\Repositories\EmojiRepository;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Infrastructure\Contracts\Http\Input;

class EmojiController extends Controller {

    public function indexAction(string $keywords = '', int $cat_id = 0) {
        return $this->renderPage(
            EmojiRepository::getList($keywords, $cat_id)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                EmojiRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string',
                'cat_id' => 'required|int',
                'type' => 'int',
                'content' => 'required|string'
            ]);
            return $this->render(
                EmojiRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int|array $id) {
        try {
            EmojiRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function categoryAction(string $keywords = '') {
        return $this->renderData(
            EmojiRepository::catList($keywords)
        );
    }

    public function categoryDetailAction(int $id) {
        try {
            return $this->render(
                EmojiRepository::getCategory($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function categorySaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string',
                'icon' => 'string'
            ]);
            return $this->render(
                EmojiRepository::saveCategory($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function categoryDeleteAction(int $id) {
        try {
            EmojiRepository::removeCategory($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function importAction() {
        $upload = new Upload();
        $upload->setDirectory(app_path()->directory('data/cache'));
        $upload->upload('file');
        if (!$upload->checkType('zip') || !$upload->save()) {
            return $this->renderFailure('文件不支持，仅支持zip文件');
        }
        $upload->each(function (BaseUpload $file) {
            EmojiRepository::import($file->getFile());
        });
        return $this->renderData(true);
    }
}