<?php
declare(strict_types=1);
namespace Module\AppStore\Service\Api;

use Module\AppStore\Domain\Repositories\AppRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CommentController extends Controller {
    public function rules() {
        return [
            '*' => '@',
            'index' => '*',
        ];
    }

    public function indexAction(string $keywords = '',
                                int $software = 0, int $user = 0, int $parent_id = -1) {
        return $this->renderPage(
            AppRepository::comment()->search($keywords, $user, $software, $parent_id)
        );
    }

    public function saveAction(Input $input, int $software) {
        try {
            $data = $input->validate([
                'content' => 'required|string:0,255',
                'parent_id' => 'int',
            ]);
            $data['target_id'] = $software;
            return $this->render(
                AppRepository::comment()->save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            AppRepository::comment()->removeBySelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function agreeAction(int $id) {
        try {
            return $this->render(
                AppRepository::comment()->agree($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function disagreeAction(int $id) {
        try {
            return $this->render(
                AppRepository::comment()->disagree($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}