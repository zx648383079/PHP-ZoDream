<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CommentController extends Controller {
    public function rules() {
        return [
            '*' => '@',
            'index' => '*',
        ];
    }

    public function indexAction(string $keywords = '',
                                int $resource = 0, int $user = 0, int $parent_id = -1) {
        return $this->renderPage(
            ResourceRepository::comment()->search($keywords, $user, $resource, $parent_id)
        );
    }

    public function saveAction(Input $input, int $resource) {
        try {
            $data = $input->validate([
                'content' => 'required|string:0,255',
                'parent_id' => 'int',
            ]);
            $data['target_id'] = $resource;
            return $this->render(
                ResourceRepository::comment()->save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ResourceRepository::comment()->removeBySelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function agreeAction(int $id) {
        try {
            return $this->render(
                ResourceRepository::comment()->agree($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function disagreeAction(int $id) {
        try {
            return $this->render(
                ResourceRepository::comment()->disagree($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}