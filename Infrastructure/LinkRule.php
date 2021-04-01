<?php
declare(strict_types=1);
namespace Infrastructure;

class LinkRule {

    public static function render(string $content, array $rules): string {
        if (empty($content) || empty($rules)) {
            return $content;
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
            return sprintf('<a href="%s">%s</a>', $rule['l'], $rule['s']);
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

    public static function formatLink(string $word, string $link): array {
        return [
            's' => $word,
            'l' => $link
        ];
    }



}