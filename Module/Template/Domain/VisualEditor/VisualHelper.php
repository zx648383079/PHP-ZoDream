<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Module\Template\Domain\Model\PageModel;
use Zodream\Helpers\Json;

class VisualHelper {

    public static function weightId(int|string $id, bool $isQueryTag = false): string {
        return sprintf('%sweight-%s', $isQueryTag ? '#' : '', $id);
    }

    public static function formatUrl(array $item): array {
        if ($item['type'] === 'url') {
            return [
                'uri' => url($item['uri']),
                'target' => $item['target']
            ];
        }
        if ($item['type'] === 'target') {
            return [
                'uri' => sprintf('javascript:lazyWeight("%d", "%s");',
                    static::weightId($item['target'], true), $item['uri']),
                'target' => '',
            ];
        }
        if ($item['type'] === 'page') {
            return [
                'uri' => url('./', ['site' => PageModel::where('id', $item['id'])->value('site_id'), 'id' => $item['id']]),
                'target' => $item['target']
            ];
        }
        return [
            'uri' => 'javascript:;',
            'target' => '',
        ];
    }

    /**
     * 转化成页面接受数据
     * @param array $items
     * @return array
     */
    public static function formatUrlTree(array $items) {
        $data = [];
        foreach ($items as $item) {
            if ($item['type'] === 'children') {
                $item['children'] = static::formatUrlTree($item['children']);
                $data[] = $item;
                continue;
            }
            $data[] = array_merge($item, static::formatUrl($item));
        }
        return $data;
    }

    /**
     * 从表单获取数据格式化
     * @param array $data
     * @return array
     */
    public static function formatUrlForm(array $data): array {
        $items = [];
        foreach ($data['title'] as $i => $title) {
            if (empty($title)) {
                continue;
            }
            $icon = $data['icon'][$i];
            $url = [
                'type' => $data['url']['type'][$i]
            ];
            if ($url['type'] === 'target') {
                $url['uri'] = $data['url']['target']['uri'][$i];
                $url['target'] = $data['url']['target']['target'][$i];
            } elseif ($url['type'] === 'url') {
                $url['uri'] = $data['url']['url']['uri'][$i];
                $url['target'] = $data['url']['url']['target'][$i];
            } else {
                $url['id'] = $data['url']['page']['id'][$i];
                $url['target'] = $data['url']['page']['target'][$i];
            }
            $items[] = compact('title', 'icon', 'url');
        }
        return $items;
    }

    public static function formatFormData($content, array $def): array {
        $items = empty($content) ? [] : Json::decode($content);
        $data = [];
        foreach($def as $key => $value) {
            if (!isset($items[$key])) {
                $data[$key] = $value;
                continue;
            }
            if (is_int($value)) {
                $data[$key] = intval($items[$key]);
            } elseif (is_array($value) && !is_array($items[$key])) {
                $data[$key] = explode(',', $items[$key]);
            } else {
                $data[$key] = $items[$key];
            }
        }
        return $data;
    }
}