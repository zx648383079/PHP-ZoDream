<?php
namespace Module\Document\Domain;

use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Parsers\CSharp;
use Module\Document\Domain\Parsers\Dart;
use Module\Document\Domain\Parsers\Go;
use Module\Document\Domain\Parsers\Kotlin;
use Module\Document\Domain\Parsers\ParserInterface;
use Module\Document\Domain\Parsers\Typescript;
use Module\Document\Domain\Parsers\Unknow;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;

class CodeParser {

    const NEW_LINE = PHP_EOL;
    const TAB = '    ';

    const TYPE_STRING = 'string';
    const TYPE_INT = 'int';
    const TYPE_FLOAT = 'float';
    const TYPE_BOOL = 'bool';
    const TYPE_DOUBLE = 'double';
    const TYPE_ARRAY = 'array';
    const TYPE_OBJECT = 'object';
    const TYPE_NULL = 'null';

    /**
     * @var ParserInterface[]
     */
    private $instances = [];

    protected $plugins = [
        CSharp::class,
        Dart::class,
        Go::class,
        Kotlin::class,
        Typescript::class,
        Unknow::class,
    ];

    protected $languages = [];

    protected function boot() {
        if (!empty($this->instances)) {
            return;
        }
        foreach ($this->plugins as $item) {
            /** @var ParserInterface $instance */
            $instance = new $item();
            $this->instances[$item] = $instance;
            foreach ($instance->languages() as $language) {
                $this->instances[$language] = $instance;
                $this->languages[] = $language;
            }
        }
    }

    /**
     * 获取插件
     * @param string $lang
     * @return ParserInterface
     */
    public function plugin(string $lang) {
        $this->boot();
        if (isset($this->instances[$lang])) {
            return $this->instances[$lang];
        }
        return $this->instances[Unknow::class];
    }

    public function getLanguages() {
        $this->boot();
        return $this->languages;
    }

    public function formatHttp($api_id, $lang) {
        $api = ApiModel::find($api_id);
        $name = str_replace(['/', '.'], '_', trim($api->uri, '/'));
        $lines = [
            $this->formatField($api_id, 1, $name.'_request', $lang),
            $this->formatField($api_id, 2, $name.'_response', $lang),
        ];
        $lines[] = $this->plugin($lang)->formatHttp($api, Str::studly($name), empty($lines[0]) ? null :
                Str::studly($name.'_request'), Str::studly($name.'_response'));
        return implode(self::NEW_LINE, $lines);
    }

    public function formatField($api_id, $kind, $name, $lang, $parent_id = 0) {
        if (empty($name)) {
            $name = 'object_item';
        }
        $api_id = $api_id ? $api_id : 0;
        $packages = $this->formatFieldItem($name, $api_id, $kind, $parent_id);
        $files = [];
        foreach ($packages as $name => $package) {
            $content = $this->formatAttribute($package, $lang);
            $files[] = $this->formatPackage($name, $content, $package, $lang);
        }
        return implode(self::NEW_LINE, $files);
    }

    public function formatFieldItem($name, $api_id, $kind, $parent_id = 0) {
        $fields = FieldModel::where('kind', $kind)->where('api_id', $api_id)
            ->where('parent_id', $parent_id)->all();
        if ($kind != FieldModel::KIND_RESPONSE && empty($fields)) {
            return [];
        }
        $package = [$name => []];
        $maps = [
            'number' => 'int',
            'boolean'=> 'bool',
        ];
        foreach ($fields as $item) {
            $type = $item['type'];
            if (isset($maps[$item['type']])) {
                $type = $maps[$item['type']];
            }
            if ($type !== self::TYPE_ARRAY && $type !== self::TYPE_OBJECT) {
                $package[$name][] = [
                    'name' => $item['name'],
                    'type' => $type
                ];
                continue;
            }
            $itemPackage = $this->formatFieldItem($item['name'], $api_id, $kind, $item['id']);
            $package[$name][] = [
                'name' => $item['name'],
                'type' => $type,
                'package' => key($itemPackage)
            ];
            $package = array_merge($package, $itemPackage);
        }
        return $package;
    }

    public function formatJson($data, $name, $lang) {
        if (is_string($data)) {
            $data = Json::decode($data);
        }
        if (empty($data)) {
            return false;
        }
        if (empty($name)) {
            $name = 'object_item';
        }
        $packages = $this->formatItem($data, $name);
        $files = [];
        foreach ($packages as $name => $package) {
            $content = $this->formatAttribute($package, $lang);
            $files[] = $this->formatPackage($name, $content, $package, $lang);
        }
        return implode(self::NEW_LINE, $files);
    }

    public function formatAttribute(array $data, $lang) {
        $lines = [];
        $plugin = $this->plugin($lang);
        foreach ($data as $item) {
            $method = lcfirst(Str::studly(sprintf('format_%s', $item['type'])));
            if (method_exists($plugin, $method)) {
                $lines[] = $plugin->{$method}($item);
                continue;
            }
            dd($method);
        }
        return implode(self::NEW_LINE, $lines);
    }

    public function formatPackage($name, $attributes, array $package, $lang) {
        return $this->plugin($lang)->formatPackage($name, $attributes, $package);
    }

    public function formatItem($data, $name) {
        $package = [$name => []];
        foreach ($data as $key => $item) {
            if (is_null($item)) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_NULL
                ];
                continue;
            }
            if (is_string($item)) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_STRING
                ];
                continue;
            }
            if (is_bool($item)) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_BOOL
                ];
                continue;
            }
            if (is_integer($item)) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_INT
                ];
                continue;
            }
            if (is_float($item) && strlen(preg_replace('/\d+\./', '', $item)) < 4) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_FLOAT
                ];
                continue;
            }
            if (is_double($item)) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_DOUBLE
                ];
                continue;
            }
            if (!is_array($item) || empty($item)) {
                continue;
            }
            $type = self::TYPE_OBJECT;
            if (!Arr::isAssoc($item)) {
                $type = self::TYPE_ARRAY;
                $item = reset($item);
            }
            $itemPackage = $this->formatItem($item, $key);
            $package[$name][] = [
                'name' => $key,
                'type' => $type,
                'package' => key($itemPackage)
            ];
            $package = array_merge($package, $itemPackage);
        }
        return $package;
    }

}