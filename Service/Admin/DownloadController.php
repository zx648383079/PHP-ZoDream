<?php
namespace Service\Admin;

use Domain\Model\EmpireModel;
use Zodream\Domain\Response\Download;
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
			$this->show();
		}
		Log::save($file, 'download');
		Download::make(APP_DIR . '/'. ltrim($file, '/'));
	}

	function recordAction() {
		$this->show();
	}

	function errorAction() {
		$page = EmpireModel::query('enewsdownerror')->getPage('order by errorid desc');
		$this->show(array(
			'page' => $page
		));
	}

	function levelAction() {
		$this->show();
	}

	function playerAction() {
		$data = EmpireModel::query('enewsplayer')->select('order by id', 'id,player,filename,bz');
		$this->show(array(
			'data' => $data
		));
	}
}