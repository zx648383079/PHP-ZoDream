<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Module\Template\Domain\VisualEditor\VisualInput;

class ShortcutWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param SiteWeightModel $model
     * @return mixed
     */
    public function render(SiteWeightModel $model): string {
        $content = $model->content;
        $key_items = [];
        foreach (explode("\n", $content) as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            $args = explode(' ', $line, 2);
            $key_items[] = [
                'name' => $args[0],
                'items' =>  $this->splitKeys($args[1])
            ];
        }
        return $this->show('view', compact('key_items'));
    }

    public function renderForm(SiteWeightModel $model): array {
        return [
            VisualInput::text('title', '分组名称', $model->title),
            VisualInput::textarea('content', '内容', $model->content, '每行一组,示例：保存 ctrl+S')
        ];
    }

    private function splitKeys(?string $line): array {
        if (empty($line)) {
            return [];
        }
        $items = [];
        $pos = 0;
        while (true) {
            $i = strpos($line, '/', $pos);
            $j = strpos($line, '+', $pos);
            if (!$i && !$j) {
                $items[] = [
                    'key' => substr($line, $pos),
                ];
                break;
            }

            if (!$i || ($j > 0 && $j < $i)) {
                $min = $j;
                $sep = '+';
            } else {
                $min = $i;
                $sep = '/';
            }
            $items[] = [
                'key' => substr($line, $pos, $min - $pos),
            ];
            $items[] = [
                'sep' => $sep,
            ];
            $pos = $min + 1;
        }
        return $items;
    }
}