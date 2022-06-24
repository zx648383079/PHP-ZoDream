<?php
declare(strict_types=1);
namespace Service\Ueditor;

use Domain\Repositories\FileRepository;
use Infrastructure\Environment;
use Zodream\Infrastructure\Contracts\Response\JsonResponse;
use Zodream\Route\Response\Json;
use Zodream\Service\Http\Request;

class HomeController extends Controller {

	protected array $configs = [];

	protected function jsonReturn(array $data) {
	    /** @var Json $json */
        $json = $this->httpContext(JsonResponse::class);
		$callback = $this->httpContext('request')->get('callback');
		if (is_null($callback)) {
			return $json->renderResponse($data, 'JSON');
		}
		if (preg_match('/^[\w_]+$/', $callback)) {
			return $json->renderResponse($data, 'JSONP');
		}
		return $this->render(array(
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
     */
	protected function upload(string $fieldName, array $config, string $base64 = 'upload') {
        try {
            $res = FileRepository::upload($fieldName, $config, $base64);
            return $this->jsonReturn(array_merge($res, [
                'state' => 'SUCCESS'
            ]));
        } catch (\Exception $ex) {
            return $this->jsonReturn([
                'state' => $ex->getMessage(),
            ]);
        }
	}

	protected function fileList(array $allowFiles, int $listSize, string $path) {
		$allowFiles = substr(str_replace('.', '|', join('', $allowFiles)), 1);

		$request = $this->httpContext('request');
		/* 获取参数 */
		$size = $request->get('size', $listSize);
		$start = $request->get('start', 0);
		$end = $start + $size;

		/* 获取文件列表 */
		$path = public_path($path)->getFullName();
		$files = Environment::getfiles($path, $allowFiles);
		if (!count($files)) {
			return $this->jsonReturn(array(
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
		return $this->jsonReturn(array(
			'state' => 'SUCCESS',
			'list' => $list,
			'start' => $start,
			'total' => count($files)
		));
	}

	public function indexAction(Request $request) {
		$action = strtolower($request->get('action'));
		if (empty($action) || !method_exists($this, $action. 'Action')) {
			return $this->jsonReturn(array(
				'state'=> '请求地址出错'
			));
		}
		$this->configs = config('ueditor');
		return $this->callMethod($action. 'Action');
	}
	
	function configAction() {
		return $this->jsonReturn($this->configs);
	}
	
	/**
	 * 上传图片
	 */
	function uploadimageAction() {
		return $this->upload($this->configs['imageFieldName'], array(
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
		$source = request()->get($fieldName, []);
		foreach ((array)$source as $imgUrl) {
            try {
                $res = FileRepository::uploadFromData($imgUrl, $config, 'remote');
                $list[] = array_merge($res, [
                    'state' => 'SUCCESS',
                    'source' => htmlspecialchars($imgUrl)
                ]);
            } catch (\Exception $ex) {
                $list[] = [
                    'state' => $ex->getMessage(),
                    'source' => htmlspecialchars($imgUrl)
                ];
            }
		}
		
		/* 返回抓取数据 */
		return $this->jsonReturn(array(
            'state'=> count($list) ? 'SUCCESS' : 'ERROR',
            'list'=> $list
		));
	}
}