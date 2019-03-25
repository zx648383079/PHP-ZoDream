<?php
namespace Module\Tool\Service;


use Zodream\Helpers\PinYin;
use Zodream\Infrastructure\Security\Hash;

class ConverterController extends Controller {

    public function indexAction($content, $type) {
        $result = $this->converter($content, $type);
        return $this->jsonSuccess(compact('content', 'type', 'result'));
    }

    protected function converter($content, $type) {
        if ($type == 'md5') {
            return md5($content);
        }
        if ($type == 'password_hash') {
            return Hash::make($content);
        }
        if ($type == 'sha1') {
            return sha1($content);
        }
        if ($type == 'base64_encode') {
            return base64_encode($content);
        }
        if ($type == 'base64_decode') {
            return base64_decode($content);
        }
        if ($type == 'pinyin') {
            return PinYin::encode($content, 'all');
        }
    }
}