<?php
namespace Module\Task\Service\Api;

use Module\Task\Domain\Repositories\CommentRepository;
use Zodream\Domain\Upload\UploadFile;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;
use Zodream\Validate\ValidationException;

class CommentController extends RestController {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction($task_id) {
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
            $file = $request->files('file');
            if (!empty($file)) {
                $upload = new UploadFile($file);
                if (!$upload->checkSize(2048000)) {
                    throw new \Exception('超出最大尺寸限制');
                }
                if (!$upload->checkType(['png', 'jpg', 'jpeg', 'gif', 'bmp'])) {
                    throw new \Exception('不允许上传此类型文件');
                }
                if (!$upload->validateDimensions()) {
                    throw new \Exception('图片尺寸有误');
                }
                $upload->setFile(public_path($upload->getRandomName('/assets/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}')));
                if (!$file->save()) {
                    throw new \Exception('上传失败');
                }
                $data['content'] = url()->asset($upload->getFile()->getRelative(public_path()));
                $data['type'] = 1;
            }
            $model = CommentRepository::create($data);
        } catch (ValidationException $ex) {
            return $this->renderFailure($ex->validator->firstError());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction($id) {
        try {
            CommentRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}