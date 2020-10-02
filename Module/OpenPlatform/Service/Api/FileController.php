<?php
namespace Module\OpenPlatform\Service\Api;

use Exception;
use Infrastructure\Environment;
use Infrastructure\Uploader;
use Zodream\Html\Page;
use Zodream\Infrastructure\Http\Output\RestResponse;
use Zodream\Route\Controller\RestController;

class FileController extends RestController {

    private $configs = [];

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function init() {
        $this->configs = config()->file('Ueditor')['ueditor'];
    }

    public function indexAction() {
        return $this->upload('file', array(
            'pathFormat' => $this->configs['filePathFormat'],
            'maxSize' => $this->configs['fileMaxSize'],
            'allowFiles' => $this->configs['fileAllowFiles']
        ));
    }

    public function base64Action() {
        return $this->upload('file', array(
            'pathFormat' => $this->configs['scrawlPathFormat'],
            'maxSize' => $this->configs['scrawlMaxSize'],
            'allowFiles' => $this->configs['scrawlAllowFiles'],
            'oriName' => 'scrawl.png'
        ), 'base64');
    }

    public function imageAction() {
        return $this->upload('file', array(
            'pathFormat' => $this->configs['imagePathFormat'],
            'maxSize' => $this->configs['imageMaxSize'],
            'allowFiles' => $this->configs['imageAllowFiles']
        ));
    }

    public function videoAction() {
        return $this->upload('file', array(
            'pathFormat' => $this->configs['videoPathFormat'],
            'maxSize' => $this->configs['videoMaxSize'],
            'allowFiles' => $this->configs['videoAllowFiles']
        ));
    }

    public function audioAction() {
        return $this->upload('file', array(
            'pathFormat' => $this->configs['videoPathFormat'],
            'maxSize' => $this->configs['videoMaxSize'],
            'allowFiles' => $this->configs['videoAllowFiles']
        ));
    }

    public function imagesAction() {
        return $this->files($this->configs['fileManagerAllowFiles'], $this->configs['imageManagerListPath']);
    }

    public function filesAction() {
        return $this->files($this->configs['imageManagerAllowFiles'], $this->configs['fileManagerListPath']);
    }

    /**
     * 列出文件
     * @param string $allow
     * @param string $path
     * @return RestResponse
     * @throws Exception
     */
    public function files($allow = '.*', $path = 'assets') {
        $allow = substr(str_replace('.', '|', implode('', $allow)), 1);
        $path = public_path($path)->getFullName();
        $files = Environment::getfiles($path, $allow);
        $page = new Page($files);
        $page->map(function ($item) {
           $item['url'] = url()->asset($item['url']);
           return $item;
        });
        return $this->renderPage($page);
    }

    /**
     * 删除文件
     * @param $fieldName
     * @param $config
     * @param string $base64
     * @return RestResponse
     * @throws Exception
     */
    protected function upload($fieldName, $config, $base64 = 'upload') {
        $upload = new Uploader($fieldName, $config, $base64);
        $res = $upload->getFileInfo();
        if ($res['state'] !== 'SUCCESS') {
            return $this->renderFailure($res['state']);
        }
        return $this->render([
            'url' => url()->asset($res['url']),
            'title' => $res['title'],
            'original' => $res['original'],
            'type' => $res['type'],
            'size' => $res['size']
        ]);
    }
}