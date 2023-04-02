<?php
declare(strict_types=1);
namespace Domain\Repositories;

use IteratorAggregate;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Database\Contracts\Table;
use Zodream\Helpers\Arr;

/**
 * 多语言控制系统
 */
class LocalizeRepository {

    const LANGUAGE_COLUMN_KEY = 'language';
    const BROWSER_DEFAULT_LANGUAGE = 'en';
    const LANGUAGE_MAP = [
        'zh' => '中',
        'en' => 'EN',
    ];

    public static function firstLanguage(): string {
        foreach (static::LANGUAGE_MAP as $key => $_) {
            return $key;
        }
        return '';
    }

    /**
     * 获取访问者语言
     * @return string
     */
    public static function browserLanguage(): string {
        $lang = strtolower((string)trans()->getLanguage());
        $hasEn = false;
        $enLang = static::BROWSER_DEFAULT_LANGUAGE;
        $firstLang = '';
        foreach (static::LANGUAGE_MAP as $key => $_) {
            if (str_contains($lang, $key)) {
                return $key;
            }
            if (empty($firstLang)) {
                $firstLang = $key;
            }
            if ($key === $enLang) {
                $hasEn = true;
            }
        }
        return $hasEn ? $enLang : $firstLang;
    }

    public static function languageOptionItems(): array {
        $items = [];
        foreach (static::LANGUAGE_MAP as $value => $name) {
            $items[] = compact('value', 'name');
        }
        return $items;
    }

    public static function addTableColumn(Table $table) {
        $languageItems = array_keys(static::LANGUAGE_MAP);
        $table->enum(static::LANGUAGE_COLUMN_KEY, $languageItems)->default(static::firstLanguage())
            ->comment('多语言配置');
    }

    /***
     * 根据查询结果进行语言替换，依赖 id 和 parent_id
     * @param array|IteratorAggregate|null $items
     * @param SqlBuilder $query
     * @return array
     * @throws \Exception
     */
    public static function formatList(array|IteratorAggregate|null $items, SqlBuilder $query): array {
        if (empty($items)) {
            return [];
        }
        $lang = static::browserLanguage();
        if ($lang === static::firstLanguage()) {
            return is_array($items) ? $items : (array)$items->getIterator();
        }
        $ids = [];
        foreach ($items as $item) {
            $ids[] = $item['id'];
        }
        if (empty($ids)) {
            return [];
        }
        $args = $query->whereIn('parent_id', $ids)
            ->where(static::LANGUAGE_COLUMN_KEY, $lang)->get();
        $data = [];
        foreach ($args as $item) {
            $data[$item['parent_id']] = $item;
        }
        $args = [];
        foreach ($items as $item) {
            $args[] = $data[$item['id']] ?? $item;
        }
        return $args;
    }

    /***
     * 根据标识获取自适应的语言版本
     * @param SqlBuilder $query
     * @param string $key
     * @param string $value
     * @return array|mixed
     */
    public static function getByKey(SqlBuilder $query, string $key, string $value) {
        $lang = static::browserLanguage();
        $firstLang = static::firstLanguage();
        if ($lang === $firstLang) {
            return $query->where(static::LANGUAGE_COLUMN_KEY, $lang)
                ->where($key, $value)->first();
        }
        $langItems = [$lang, $firstLang];
        if (isset(static::LANGUAGE_MAP[static::BROWSER_DEFAULT_LANGUAGE]) && !in_array(static::BROWSER_DEFAULT_LANGUAGE, $langItems)) {
            $langItems[] = static::BROWSER_DEFAULT_LANGUAGE;
        }
        return $query->whereIn(static::LANGUAGE_COLUMN_KEY, $langItems)
            ->where($key, $value)->first();
    }

    /***
     * 一篇文章可以切换的获取语言切换标识
     * @param array $items
     * @param bool $justExist
     * @return array
     */
    public static function formatLanguageList(array $items, bool $justExist = true): array {
        $maps = [];
        foreach ($items as $item) {
            $lang = $item[static::LANGUAGE_COLUMN_KEY];
            unset($item[static::LANGUAGE_COLUMN_KEY]);
            $maps[$lang] = $item;
        }
        $data = [];
        foreach (static::LANGUAGE_MAP as $value => $name) {
            if ($justExist && !isset($maps[$value])) {
                continue;
            }
            $item = compact('value', 'name');
            $data[] = isset($maps[$value]) ? array_merge(Arr::toArray($maps[$value]), $item) : $item;
        }
        return $data;
    }
}