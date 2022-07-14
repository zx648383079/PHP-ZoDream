<?php
declare(strict_types=1);
namespace Module\Document\Service\Api;

use Module\Document\Domain\Repositories\ProjectRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CommentController extends Controller {

    public function rules() {
        return [
            '*' => '@',
            'index' => '*',
        ];
    }

    public function indexAction(string $keywords = '',
                                int $target = 0, int $user = 0, int $parent_id = -1) {
        return $this->renderPage(
            ProjectRepository::comment()->search($keywords, $user, $target, $parent_id)
        );
    }

    public function saveAction(Input $input, int $target) {
        try {
            $data = $input->validate([
                'content' => 'required|string:0,255',
                'parent_id' => 'int',
            ]);
            $data['target_id'] = $target;
            return $this->render(
                ProjectRepository::comment()->save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ProjectRepository::comment()->removeBySelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}