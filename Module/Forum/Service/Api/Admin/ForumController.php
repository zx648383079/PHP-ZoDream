<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api\Admin;

use Module\Forum\Domain\Repositories\ForumRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ForumController extends Controller {

    public function indexAction(string $keywords = '', int $parent = 0) {
        return $this->renderPage(
            ForumRepository::getList($keywords, $parent)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ForumRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'thumb' => 'string:0,100',
                'description' => 'string:0,255',
                'parent_id' => 'int',
                'type' => 'int:0,99',
                'position' => 'int:0,999',
                'classifies' => '',
                'moderators' => '',
            ]);
            return $this->render(
                ForumRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ForumRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            ForumRepository::all()
        );
    }

}