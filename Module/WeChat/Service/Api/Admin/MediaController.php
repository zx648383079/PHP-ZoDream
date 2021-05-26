<?php
namespace Module\WeChat\Service\Api\Admin;

use Domain\Model\SearchModel;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\TemplateModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Repositories\MediaRepository;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\ThirdParty\WeChat\Media;

class MediaController extends Controller {


    public function indexAction(string $keywords = '', string $type = '') {
        return $this->renderPage(MediaRepository::getList($keywords, $type));
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                MediaRepository::get($id)
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
            $data['wid'] = $this->weChatId();
            return $this->render(
                MediaRepository::save($data)
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
}