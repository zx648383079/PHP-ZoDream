<?php
namespace Module\Task\Service\Api;

use Domain\Repositories\FileRepository;
use Module\Task\Domain\Repositories\CommentRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Validate\ValidationException;

class CommentController extends Controller {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction(int $task_id) {
        return $this->renderPage(
            CommentRepository::getList($task_id)
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