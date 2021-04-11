<?php
declare(strict_types=1);
namespace Module\Document\Domain\Repositories;

use Exception;
use Module\Document\Domain\MockRule;
use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Json;
use Zodream\Helpers\Xml;
use Zodream\Html\Tree;
use Zodream\Http\Http;
use Zodream\Http\Uri;
use Zodream\Infrastructure\Contracts\Http\Input;

class MockRepository {

    public static function request(Input $input) {
        $url = new Uri($input->get('url'));
        $method = $input->get('method');
        $data = $input->get('request');
        $realData = [];
        if (!empty($data) && isset($data['key'])) {
            foreach ($data['key'] as $i => $item) {
                $realData[$item] = $data['value'][$i];
            }
        }
        $header = $input->get('header');
        $headers = [
            'request' => [],
            'response' => []
        ];
        $realHeader = [];
        if (!empty($header) && isset($header['key'])) {
            foreach ($header['key'] as $i => $item) {
                $headers['request'][] = sprintf('%s: %s', $item, $header['value'][$i]);
                $realHeader[$item] = $header['value'][$i];
            }
        }
        if ($method != 'POST') {
            $url->setData($realData);
        }
        $http = new Http($url);
        $body = $http->header($realHeader)
            ->maps($realData)->method($method)->setHeaderOption(true)
            ->setOption(CURLOPT_RETURNTRANSFER, 1)
            ->setOption(CURLOPT_FOLLOWLOCATION, 1)
            ->setOption(CURLOPT_AUTOREFERER, 1)->getResponseText();
        $info = $http->getResponseHeader();
        $headers['response'] = explode(PHP_EOL, substr($body, 0, $info['header_size']));
        $body = substr($body, $info['header_size']);
        return compact('body', 'headers', 'info');
    }

    public static function mockValue($mock) {
        if (empty($mock)) {
            return null;
        }
        $data = explode('|', $mock);
        $type = $data[0];
        if (!$type){
            return $mock;
        }
        $rule = $data[1] ?? '';
        $value = $data[2] ?? '';
        $mock = new MockRule();
        if ($type == 'array') {
            $type = 'arr';
        }
        if(!method_exists($mock, $type)){
            return $mock;
        }
        return $mock->$type($rule, $value);
    }

    /**
     * 获取响应字段默认值数组
     * @param int $api_id
     * @param int $parent_id
     * @return mixed
     */
    public static function getDefaultData(int $api_id, int $parent_id = 0) {
        $fields = FieldModel::where('kind', FieldModel::KIND_RESPONSE)->where('api_id', $api_id)
            ->where('parent_id', $parent_id)->get();
        $data = [];
        foreach ($fields as $k => $v){
            $name = $v['name'];
            if($v['type'] == 'array'){
                $data[$name][] = self::getDefaultData($api_id, $v['id']);
                continue;
            }
            if($v['type'] == 'object'){
                $data[$name] = self::getDefaultData($api_id, $v['id']);
                continue;
            }
            if ($v['default_value'] === '' && $v['mock']) {
                $v->setMock();
            }
            $data[$name] = self::format($v['type'], $v['default_value']);
        }
        return $data;

    }

    public static function format($type, $val) {
        if ($type === 'number') {
            return floatval($val);
        }
        if ($type === 'boolean') {
            if (is_bool($val)) {
                return $val;
            }
            return $val === 'true';
        }
        return $val;
    }

    /**
     * 获取响应字段mock数组
     * @param $api_id
     * @param int $parent_id
     * @return mixed
     */
    public static function getMockData(int $api_id, int $parent_id = 0) {
        $api_id = $api_id ? $api_id : 0;
        $fields = FieldModel::where('kind', FieldModel::KIND_RESPONSE)->where('api_id', $api_id)->where('parent_id', $parent_id)->all();
        $data = [];
        foreach ($fields as $k => $v){
            $name = $v['name'];
            if ($v['type'] == 'array'){
                $value = self::getMockData($api_id, $v['id']);
                $data[$name][] = $value ? $value : array();
                continue;
            }
            if($v['type'] == 'object'){
                $value = self::getMockData($api_id, $v['id']);
                $data[$name] = $value ? $value : (object)array();
                continue;
            }
            $data[$name] = self::format($v->type, $v->getMockValueAttribute());
        }
        return $data;

    }

    /**
     * @param string $content
     * @param int $kind
     * @return FieldModel[]
     */
    public static function parseContent(string $content, int $kind = FieldModel::KIND_REQUEST) {
        if ($kind === FieldModel::KIND_HEADER) {
            return self::parseHeader($content);
        }
        if (substr($content, 0, 1) == '{') {
            $args = Json::decode($content);
        } elseif (substr($content, 0, 1) == '<') {
            $args = Xml::specialDecode(preg_replace('/^[\s\S]*\<xml\>/', '<xml>', $content));
        } else {
            $args = [];
            parse_str($content, $args);
        }
        $data = [];
        foreach ($args as $key => $item) {
            $model = new FieldModel([
                'name' => $key,
                'title' => $key,
                'type' => 'string',
                'kind' => $kind,
                'default_value' => is_null($item) || is_array($item) || strlen((string)$item) > 30 ? '' : $item,
                'is_required' => !empty($item) ? 1 : 0,
                'level' => 0,
            ]);
            self::parseChildren($item, $model);
            $data[] = $model;
        }
        return $data;
    }

    public static function parseChildren($content, FieldModel $model) {
        if (is_null($content)) {
            $model->type = 'null';
        }
        if (is_bool($content)) {
            $model->type = 'boolean';
            return;
        }
        if (is_float($content)) {
            $model->type = 'float';
            return;
        }
        if (is_double($content)) {
            $model->type = 'double';
            return;
        }
        if (is_numeric($content)) {
            $model->type = !str_contains((string)$content, '.') ? 'number' : 'float';
            return;
        }
        if (!is_array($content)) {
            return;
        }
        $model->default_value = '';
        $model->type = Arr::isAssoc($content) ? 'object' : 'array';
        $data = $model->type == 'array' ? reset($content) : $content;
        foreach ($data as $key => $item) {
            $child = new FieldModel([
                'name' => $key,
                'title' => $key,
                'type' => 'string',
                'default_value' => is_null($item) || is_array($item) || strlen((string)$item) > 30 ? '' : $item,
                'is_required' => !empty($item),
                'level' => $model->level + 1,
            ]);
            self::parseChildren($item, $child);
            $model->children[] = $child;
        }
    }

    public static function parseHeader($content) {
        $data = [];
        foreach (explode("\n", $content) as $line) {
            if (!str_contains($line, ':')) {
                continue;
            }
            list($key, $value) = explode(':', $line, 2);
            $key = trim($key);
            if (empty($key)) {
                continue;
            }
            $value = trim($value);
            $data[] = new FieldModel([
                'name' => $key,
                'title' => $key,
                'default_value' => $value,
                'type' => 'string',
                'kind' => FieldModel::KIND_HEADER,
                'is_required' => !empty($value)
            ]);
        }
        return $data;
    }
}