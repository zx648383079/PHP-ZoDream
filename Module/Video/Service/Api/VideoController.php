<?php
declare(strict_types=1);
namespace Module\Video\Service\Api;

use Module\Video\Domain\Repositories\VideoRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class VideoController extends Controller {

    public function rules() {
        return [
            '*' => '@',
            'index' => '*',
        ];
    }

    public function indexAction(string $keywords = '', int $user = 0, int $music = 0, $id = null) {
        return $this->renderPage(
            VideoRepository::getList($keywords, $user, $music, $id)
        );
    }

    /**
     * 无尽模式
     * @param string $keywords
     * @param int $user
     * @param int $music
     */
    public function moreAction(string $keywords = '', int $user = 0, int $music = 0) {
        return $this->renderPage(
            VideoRepository::moreList($keywords, $user, $music)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                VideoRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'cover' => 'string:0,255',
                'content' => 'string:0,255',
                'video_path' => 'required|string:0,255',
                'video_duration' => 'int:0,9999',
                'video_height' => 'int:0,9999',
                'video_width' => 'int:0,9999',
                'music_id' => 'required|int',
                'music_offset' => 'int:0,999',
                'status' => 'int:0,127',
            ]);
            return $this->render(
                VideoRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function likeAction(int $id) {
        try {
            return $this->render(
                VideoRepository::like($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            VideoRepository::removeBySelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function userAction(int $id) {
        try {
            return $this->render(
                VideoRepository::user($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}