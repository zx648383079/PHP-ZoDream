<?php
namespace Service\Api;


use Zodream\Domain\Model;
use Zodream\Domain\Response\AjaxResponse;
use Zodream\Domain\Response\Redirect;
use Zodream\Domain\Controller\Controller as BaseController;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\Url\Url;
use Zodream\Infrastructure\Log;

abstract class Controller extends BaseController {
    protected $type;

	protected function rules() {
		return array(
			'*' => '@'
		);
	}

	public function prepare() {
        parent::prepare();
        $this->type = Request::get('format', AjaxResponse::JSON);
    }

    /**
     * RETURN SUCCESS JSON
     * @param mixed $data
     * @return \Zodream\Domain\Response\AjaxResponse
     */
	public function success($data) {
        return $this->ajax([
            'status' => 'success',
            'code' => 0,
            'data' => $data
        ]);
    }

    /**
     * RETURN FAILURE JSON
     * @param int $code
     * @param string $message
     * @return \Zodream\Domain\Response\AjaxResponse
     */
    public function failure($code = 1, $message = null) {
        return $this->ajax([
            'status' => 'failure',
            'code' => $code,
            'message' => $message
        ]);
    }
}