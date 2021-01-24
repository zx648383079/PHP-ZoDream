<?php
declare(strict_types=1);
namespace Module\Video\Service\Api;

use Module\Video\Domain\Repositories\CommentRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CommentController extends Controller {

    public function rules() {
        return [
            '*' => '@',
            'index' => '*',
        ];
    }

    public function indexAction(string $keywords = '', int $video = 0, int $user = 0) {
        return $this->renderPage(
            CommentRepository::getAllList($keywords, $video, $user)
        );
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'content' => 'required|string:0,255',
                'parent_id' => 'int',
                'video_id' => 'required|int',
            ]);
            return $this->render(
                CommentRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            CommentRepository::removeBySelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}