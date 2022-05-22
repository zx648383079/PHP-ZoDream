<?php
declare(strict_types=1);
namespace Module\Video\Service\Api;

use Module\Video\Domain\Repositories\VideoRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CommentController extends Controller {

    public function rules() {
        return [
            '*' => '@',
            'index' => '*',
        ];
    }

    public function indexAction(string $keywords = '',
                                int $video = 0, int $user = 0, int $parent_id = -1) {
        return $this->renderPage(
            VideoRepository::comment()->search($keywords, $user, $video, $parent_id)
        );
    }

    public function saveAction(Input $input, int $video_id) {
        try {
            $data = $input->validate([
                'content' => 'required|string:0,255',
                'parent_id' => 'int',
            ]);
            $data['target_id'] = $video_id;
            return $this->render(
                VideoRepository::comment()->save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            VideoRepository::comment()->removeBySelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}