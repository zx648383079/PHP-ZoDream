<?php
namespace Service\Admin;

use Zodream\Infrastructure\Factory;
use Zodream\Infrastructure\Http\Request;

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
		$file = Request::get('file');
		if (empty($file)) {
			return $this->show();
		}
        $file = Factory::root()->childFile($file);
		Factory::log()->info('download', ['file' => $file]);
		return Factory::response()->sendFile($file);
	}
}