<?php
declare(strict_types=1);
namespace Module\Task\Service\Api;

use Domain\Repositories\FileRepository;
use Module\Task\Domain\Repositories\CommentRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class CommentController extends Controller {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction(int $task) {
        return $this->renderPage(
            CommentRepository::getList($task)
        );
    }

    public function saveAction(Request $request) {
        try {
            $data = $request->validate([
                'task_id' => 'required|int',
                'content' => 'string:0,255',
                'type' => 'int:0,127',
            ]);
            $file = $request->file('file');
            if (!empty($file)) {
                $item = FileRepository::uploadImage();
                $data['content'] = $item['url'];
                $data['type'] = 1;
            }
            $model = CommentRepository::create($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            CommentRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}