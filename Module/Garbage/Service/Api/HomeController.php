<?php
namespace Module\Garbage\Service\Api;

use Zodream\Helpers\Json;
use Zodream\Route\Controller\RestController;
use Zodream\Service\Factory;

class HomeController extends RestController {

    public function indexAction($keywords) {
        $keywords = trim($keywords);
        if (empty($keywords)) {
            return $this->render([]);
        }
        $data = $this->getGarbage();
        $i = $this->getBestGarbage($keywords, $data['garbage']);
        $item = [
            'name' => $data['garbage'][$i]['n'],
            'classification' => $data['classification'][$data['garbage'][$i]['c'] - 1]
        ];
        return $this->render($item);
    }

    public function classificationAction() {
        return $this->render($this->getGarbage('classification'));
    }

    public function suggestAction($keywords) {
        $keywords = trim($keywords);
        if (empty($keywords)) {
            return $this->render([]);
        }
        $data = $this->getGarbage('garbage');
        $args = [];
        foreach ($data as $item) {
            if (strpos($item['n'], $keywords) === false) {
                continue;
            }
            $args[] = $item['n'];
            if (count($args) > 10) {
                break;
            }
        }
        return $this->render($args);
    }

    protected function getBestGarbage($name, array $data) {
        $score = 0;
        $index = 0;
        foreach ($data as $key => $item) {
            if ($item['n'] === $name) {
                return $key;
            }
            if (strpos($item['n'], $name) === false) {
                continue;
            }
            $temp = strlen($item['n']) - strlen($name);
            if ($score > 0 && $temp < $score) {
                $index = $key;
                $score = $temp;
            }
        }
        return $index;
    }

    protected function getGarbage($name = null) {
        $file = Factory::root()->file('Module/Garbage/garbage.json');
        $data = Json::decode($file->read());
        return empty($name) ? $data : $data[$name];
    }
}