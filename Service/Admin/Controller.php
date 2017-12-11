<?php
namespace Service\Admin;


use Zodream\Infrastructure\Interfaces\ArrayAble;
use Zodream\Route\Controller\Controller as BaseController;

abstract class Controller extends BaseController {
	
	protected function rules() {
		return array(
			'*' => '*'
		);
	}


	public function prepare() {
		/*$model = new MessagesModel();
		$tasks = new TasksModel();
		$this->send(array(
			'usermessages' => $model->findTitle(),
			'noread' => $model->findNoReaded(),
			'newtasks' => $tasks->findNewTasks()
		));*/
	}

    /**
     * ajax 成功返回
     * @param null $data
     * @param null $message
     * @return Response
     */
    public function jsonSuccess($data = null, $message = null) {
        if (!is_array($message)) {
            $message = ['message' => $message];
        }
        if ($data instanceof ArrayAble) {
            $data = $data->toArray();
        }
        return $this->json(array_merge(array(
            'code' => 200,
            'status' => 'success',
            'data' => $data
        ), $message));
    }

    /**
     * ajax 失败返回
     * @param string|array $message
     * @param int $code
     * @return Response
     */
    public function jsonFailure($message = '', $code = 400) {
        if (is_array($message)) {
            return $this->json(array(
                'code' => $code,
                'status' => 'failure',
                'errors' => $message
            ));
        }
        return $this->json(array(
            'code' => $code,
            'status' => 'failure',
            'message' => $message
        ));
    }
}