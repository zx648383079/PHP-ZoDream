<?php
namespace Service\Api;

use Zodream\Infrastructure\Http\Response;
use Zodream\Route\Controller\RestController;

abstract class Controller extends RestController {

	public function rules() {
		return array(
			'*' => '@'
		);
	}

    /**
     * RETURN SUCCESS JSON
     * @param mixed $data
     * @return Response
     */
	public function success($data) {
        return $this->json([
            'status' => 'success',
            'code' => 0,
            'data' => $data
        ]);
    }

    /**
     * RETURN FAILURE JSON
     * @param int $code
     * @param string $message
     * @return Response
     */
    public function failure($code = 1, $message = null) {
        return $this->json([
            'status' => 'failure',
            'code' => $code,
            'message' => $message
        ]);
    }
}