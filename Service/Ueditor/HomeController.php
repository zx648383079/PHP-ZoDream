<?php
namespace Service\Ueditor;

use Zodream\Infrastructure\Config;
use Zodream\Infrastructure\Http\Request;
use Infrastructure\Uploader;
use Zodream\Infrastructure\Response;

class HomeController extends Controller {

	protected $configs = array();

	protected function ajaxReturn($data) {
		$callback = Request::get('callback');
		if (is_null($callback)) {
			return $this->ajax($data, 'JSON');
		}
		if (preg_match('/^[\w_]+$/', $callback)) {
			return $this->ajax($data, 'JSONP');
		}
		return $this->ajax(array(
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
     * @param $fieldName
     * @param $config
     * @param string $base64
     * @return Response
     */
	protected function upload($fieldName, $config, $base64 = 'upload') {
		$upload = new Uploader($fieldName, $config, $base64);
		return $this->ajaxReturn($upload->getFileInfo());
	}

	protected function fileList($allowFiles, $listSize, $path) {
		$allowFiles = substr(str_replace('.', '|', join('', $allowFiles)), 1);

		/* 获取参数 */
		$size = Request::get('size', $listSize);
		$start = Request::get('start', 0);
		$end = $start + $size;

		/* 获取文件列表 */
		$path = APP_DIR . (substr($path, 0, 1) == '/' ? '':'/') . $path;
		$files = Environment::getfiles($path, $allowFiles);
		if (!count($files)) {
			return $this->ajaxReturn(array(
                'state' => 'no match file',
                'list' => array(),
                'start' => $start,
                'total' => count($files)
            ));
		}

		/* 获取指定范围的列表 */
		$len = count($files);
		for ($i = min($end, $len) - 1, 
			 $list = array(); 
			 $i < $len && $i >= 0 && $i >= $start; 
			 $i --){
			$list[] = $files[$i];
		}
		//倒序
		//for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
		//    $list[] = $files[$i];
		//}
		return $this->ajaxReturn(array(
			'state' => 'SUCCESS',
			'list' => $list,
			'start' => $start,
			'total' => count($files)
		));
	}

	public function indexAction() {
		$action = strtolower(Request::get('action'));
		if (is_null($action) || !$this->canRunAction($action)) {
			return $this->ajaxReturn(array(
				'state'=> '请求地址出错'
			));
		}
		$this->configs = Config::getValue('ueditor');
		return $this->runAction($action);
	}
	
	function configAction() {
		return $this->ajaxReturn($this->configs);
	}
	
	/**
	 * 上传图片
	 */
	function uploadimageAction() {
		$this->upload($this->configs['imageFieldName'], array(
				'pathFormat' => $this->configs['imagePathFormat'],
				'maxSize' => $this->configs['imageMaxSize'],
				'allowFiles' => $this->configs['imageAllowFiles']
		));
	}
	
	/**
	 * 上传涂鸦
	 */
	function uploadscrawlAction() {
        return $this->upload($this->configs['scrawlFieldName'], array(
            'pathFormat' => $this->configs['scrawlPathFormat'],
            'maxSize' => $this->configs['scrawlMaxSize'],
            'allowFiles' => $this->configs['scrawlAllowFiles'],
            'oriName' => 'scrawl.png'
        ), 'base64');
	}
	
	/**
	 * 上传视频
	 */
	function uploadvideoAction() {
		return $this->upload($this->configs['videoFieldName'], array(
            'pathFormat' => $this->configs['videoPathFormat'],
            'maxSize' => $this->configs['videoMaxSize'],
            'allowFiles' => $this->configs['videoAllowFiles']
        ));
	}
	
	/**
	 * 上传文件
	 */
	function uploadfileAction() {
		return $this->upload($this->configs['fileFieldName'], array(
            'pathFormat' => $this->configs['filePathFormat'],
            'maxSize' => $this->configs['fileMaxSize'],
            'allowFiles' => $this->configs['fileAllowFiles']
        ));
	}
	
	/**
	 * 列出文件
	 */
	function listfileAction() {
		return $this->fileList($this->configs['fileManagerAllowFiles'], $this->configs['fileManagerListSize'], $this->configs['fileManagerListPath']);
	}
	
	/**
	 * 列出图片
	 */
	function listimageAction() {
		return $this->fileList($this->configs['imageManagerAllowFiles'], $this->configs['imageManagerListSize'], $this->configs['imageManagerListPath']);
	}
	
	/**
	 * 抓取远程文件
	 */
	function catchimageAction() {
		set_time_limit(0);
		$config = array(
				'pathFormat' => $this->configs['catcherPathFormat'],
				'maxSize' => $this->configs['catcherMaxSize'],
				'allowFiles' => $this->configs['catcherAllowFiles'],
				'oriName' => 'remote.png'
		);
		$fieldName = $this->configs['catcherFieldName'];
		
		/* 抓取远程图片 */
		$list = array();
		$source = Request::post($fieldName, Request::get($fieldName));
		foreach ($source as $imgUrl) {
			$item = new Uploader($imgUrl, $config, 'remote');
			$info = $item->getFileInfo();
			array_push($list, array(
					'state' => $info['state'],
					'url' => $info['url'],
					'size' => $info['size'],
					'title' => htmlspecialchars($info['title']),
					'original' => htmlspecialchars($info['original']),
					'source' => htmlspecialchars($imgUrl)
			));
		}
		
		/* 返回抓取数据 */
		return $this->ajaxReturn(array(
				'state'=> count($list) ? 'SUCCESS':'ERROR',
				'list'=> $list
		));
	}
}