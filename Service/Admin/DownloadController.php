<?php
namespace Service\Admin;


use Zodream\Domain\Response\FileResponse;
use Zodream\Infrastructure\Log;
use Zodream\Infrastructure\Request;

class DownloadController extends Controller {
	protected function rules() {
		return array(
			'*' => '*'
		);
	}

	/**
	 * 管理地址前缀
	 */
	function indexAction() {
		$file = Request::get('file').str_replace('../');
		if (empty($file)) {
			$this->show();
		}
		Log::save($file, 'download');
		return new FileResponse(APP_DIR . '/'. ltrim($file, '/'));
	}

	function recordAction() {
		return $this->show();
	}

	function levelAction() {
		return $this->show();
	}
}