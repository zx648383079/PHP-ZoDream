<?php
namespace Service\Ueditor;

use Zodream\Infrastructure\Config;
use Zodream\Infrastructure\Request;
use Infrastructure\Uploader;
class HomeController extends Controller {
	
	function configAction() {
		$this->ajax($this->configs);
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
        $this->upload($this->configs['scrawlFieldName'], array(
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
		$this->upload($this->configs['videoFieldName'], array(
            'pathFormat' => $this->configs['videoPathFormat'],
            'maxSize' => $this->configs['videoMaxSize'],
            'allowFiles' => $this->configs['videoAllowFiles']
        ));
	}
	
	/**
	 * 上传文件
	 */
	function uploadfileAction() {
		$this->upload($this->configs['fileFieldName'], array(
            'pathFormat' => $this->configs['filePathFormat'],
            'maxSize' => $this->configs['fileMaxSize'],
            'allowFiles' => $this->configs['fileAllowFiles']
        ));
	}
	
	/**
	 * 列出文件
	 */
	function listfileAction() {
		$this->fileList($this->configs['fileManagerAllowFiles'], $this->configs['fileManagerListSize'], $this->configs['fileManagerListPath']);
	}
	
	/**
	 * 列出图片
	 */
	function listimageAction() {
		$this->fileList($this->configs['imageManagerAllowFiles'], $this->configs['imageManagerListSize'], $this->configs['imageManagerListPath']);
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
		$source = Request::getInstance()->post($fieldName, Request::getInstance()->get($fieldName));
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
		$this->ajax(array(
				'state'=> count($list) ? 'SUCCESS':'ERROR',
				'list'=> $list
		));
	}
}