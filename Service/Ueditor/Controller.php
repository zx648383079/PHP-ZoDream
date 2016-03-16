<?php
namespace Service\Ueditor;

use Zodream\Domain\Routing\BaseController;
use Zodream\Infrastructure\Traits\AjaxTrait;
use Zodream\Infrastructure\Request;
use Infrastructure\Uploader;
use Zodream\Infrastructure\Config;
use Infrastructure\Environment;
abstract class Controller extends BaseController {
	use AjaxTrait;
	
	protected $configs = array();
	
	protected function ajax($data) {
		$callback = Request::getInstance()->get('callback');
		if (is_null($callback)) {
			$this->ajaxReturn($data, 'JSON');
		}
		if (preg_match('/^[\w_]+$/', $callback)) {
			$this->ajaxReturn($data, 'JSONP');
		}
		$this->ajaxReturn(array(
					'state'=> 'callback参数不合法'
		));
	}
	
	/**
	 * 得到上传文件所对应的各个参数,数组结构
	 * array(
	 *     'state' => '',          //上传状态，上传成功时必须返回'SUCCESS'
	 *     'url' => '',            //返回的地址
	 *     'title' => '',          //新文件名
	 *     'original' => '',       //原始文件名
	 *     'type' => ''            //文件类型
	 *     'size' => '',           //文件大小
	 * )
	 */
	protected function upload($fieldName, $config, $base64 = 'upload') {
		$upload = new Uploader($fieldName, $config, $base64);
		$this->ajax($upload->getFileInfo());
	}
	
	protected function fileList($allowFiles, $listSize, $path) {
		$allowFiles = substr(str_replace('.', '|', join('', $allowFiles)), 1);
		
		/* 获取参数 */
		$size = Request::getInstance()->get('size', $listSize);
		$start = Request::getInstance()->get('start', 0);
		$end = $start + $size;
		
		/* 获取文件列表 */
		$path = APP_DIR . (substr($path, 0, 1) == '/' ? '':'/') . $path;
		$files = Environment::getfiles($path, $allowFiles);
		if (!count($files)) {
			return json_encode(array(
					'state' => 'no match file',
					'list' => array(),
					'start' => $start,
					'total' => count($files)
			));
		}
		
		/* 获取指定范围的列表 */
		$len = count($files);
		for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
			$list[] = $files[$i];
		}
		//倒序
		//for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
		//    $list[] = $files[$i];
		//}
		$this->ajax(array(
		    'state' => 'SUCCESS',
		    'list' => $list,
		    'start' => $start,
		    'total' => count($files)
		));
	}
	
	public function indexAction() {
		$action = strtolower(Request::getInstance()->get('action'));
		if (is_null($action) || !$this->canRunAction($action)) {
			$this->ajax(array(
	            'state'=> '请求地址出错'
	        ));
		}
		$this->configs = Config::getInstance()->get('upload');
		$this->runAction($action);
	}
}