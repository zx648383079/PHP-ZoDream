<?php
declare(strict_types=1);
namespace Infrastructure;

use Zodream\Helpers\Json;

class LinkRule {

    public static function render(string $content, array|string $rules): string {
        if (empty($content) || empty($rules)) {
            return $content;
        }
        if (!is_array($rules)) {
            $rules = Json::decode($rules);
        }
        $search = [];
        $replace = [];
        foreach ($rules as $item) {
            $search[] = $item['s'];
            $replace[] = static::renderReplace($item);
        }
        return str_replace($search, $replace, $content);
    }

    protected static function renderReplace(array $rule): string {
        if (isset($rule['i'])) {
            return sprintf('<img src="%s" alt="%s">', $rule['i'], $rule['s']);
        }
        if (isset($rule['f'])) {
            return sprintf('<a href="%s" download>%s</a>', $rule['f'], $rule['s']);
        }
        if (isset($rule['u'])) {
            return static::renderUser($rule);
        }
        if (isset($rule['l'])) {
            return sprintf('<a href="%s">%s</a>', Deeplink::decode($rule['l']), $rule['s']);
        }
        return static::renderExtra($rule);
    }

    protected static function renderExtra(array $rule): string {
        return '';
    }

    protected static function renderUser(array $rule): string {
        return sprintf('<a href="%d">%s</a>', $rule['u'], $rule['s']);
    }

    public static function formatRule(string $word, array $rule): array {
        $rule['s'] = $word;
        return $rule;
    }

    public static function formatUser(string $word, int $user): array {
        return [
            's' => $word,
            'u' => $user
        ];
    }

    public static function formatImage(string $word, string $image): array {
        return [
            's' => $word,
            'i' => $image
        ];
    }

    public static function formatFile(string $word, string $file): array {
        return [
            's' => $word,
            'f' => $file
        ];
    }

    /**
     * 跳转到id
     * @param string $word
     * @param string $id
     * @return string[]
     */
    public static function formatId(string $word, string $id): array {
        return [
            's' => $word,
            'l' => '#'.$id
        ];
    }

    public static function formatLink(string $word, string $link, array $params = []): array {
        return [
            's' => $word,
            'l' => str_contains($link, '://') && empty($params) ? $link : Deeplink::encode($link, $params)
        ];
    }
}