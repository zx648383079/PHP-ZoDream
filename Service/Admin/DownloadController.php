<?php
namespace Service\Admin;


use Zodream\Domain\Response\FileResponse;
use Zodream\Infrastructure\Factory;
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
		$file = Request::get('file');
		if (empty($file)) {
			return $this->show();
		}
        $file = Factory::root()->childFile($file);
		Log::save($file, 'download');
		return new FileResponse($file);
	}
}